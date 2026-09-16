<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::select('id', 'name'); 

        if ($request->ajax()) {   //javascript sy jab ajax call ki toh , browser header main-> yeh aek ajax request hy, bta deta hy
            return DataTables::of($roles)
                ->addColumn('action', function ($row) {
                     $buttons = '';
                    if (auth()->user()->can('edit-roles')) {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $row->id . '">Edit</a> ';
                    }
                    if (auth()->user()->can('add-roles')) {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning permButton" data-id="' . $row->id . '">Permissions</a> ';
                    }
                    if (auth()->user()->can('delete-roles')) {
                        $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delButton" data-id="' . $row->id . '">Delete</a>';
                    }
                
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.role');
    }

    public function store(Request $request)
    {
        if ($request->role_id) {
            // Update
            $role = Role::findOrFail($request->role_id);
            $role->update([
                'name' => $request->name
            ]);

            return response()->json(['success' => 'Role Updated Successfully'], 201);
        } else {
            // Create
            $request->validate([
                'name' => 'required|min:2|max:50|unique:roles,name'
            ]);
            Role::create(['name' => $request->name]);
            return response()->json(['success' => 'Role Created Successfully'], 201);
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return $role;
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['success' => 'Role Deleted Successfully'], 201);
    }

// ===================== Permissions to roles ==================================
public function getPermissions($id)
{
    $role = Role::findOrFail($id);

    // Get all permissions grouped by module_name
    $allPermissions = Permission::select('id', 'name', 'module_name', 'parent')
                        ->orderBy('module_name')
                        ->orderBy('parent')
                        ->orderBy('name')
                        ->get()
                        ->groupBy('module_name');

    // IDs already assigned to this role
    $rolePermissions = $role->permissions->pluck('id')->toArray();

    return response()->json([
        'role'            => $role,
        'allPermissions'  => $allPermissions,
        'rolePermissions' => $rolePermissions,
    ]);
}

public function syncPermissions(Request $request, $id)
{
    $role = Role::findOrFail($id);
    $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();
    $role->syncPermissions($permissions);
    return response()->json(['success' => 'Permissions synced successfully']);
}

}