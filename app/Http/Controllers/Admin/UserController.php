<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'name', 'username', 'email', 'phone_num');
        if($request->ajax()){
            return DataTables::of($users)
            ->addColumn('roles', function($user){
                return $user->roles->pluck('name')->implode(', ') ?: '<span class="badge bg-danger">No Role</span>';
            })->addColumn('action', function($user){
                $buttons = '';
                if(auth()->user()->can('edit-users')){
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-info editButton" data-id="' . $user->id . '">Edit</a> ';
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning assignRoleBtn" data-id="' . $user->id . '">Assign Role</a> ';
                }
                if(auth()->user()->can('delete-users')){
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger delButton" data-id="' . $user->id . '">Delete</a>';
                }
                 return $buttons;
            })
            ->rawColumns(['action', 'roles']) // HTML (badges aur buttons) ko render karne ki ijazat
            ->make(true);
            }

        return view('users.index');
    }

    public function store(Request $request){
        if($request->user_id)
        {
            $user = User::findOrFail($request->user_id);
            $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email,' . $user->id,
                'username'  => 'nullable|string|max:50|unique:users,username,' . $user->id,
                'phone_num' => 'nullable|string|max:20',
            ]);

            $data = [
                'name'      => $request->name,
                'email'     => $request->email,
                'username'  => $request->username,
                'phone_num' => $request->phone_num,
            ];

            //conditional password change -> agr password field enter ki toh :
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
             $user->update($data);
            return response()->json(['success' => 'User Updated Successfully']);

        } else {
            $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|min:6', // Naye user ke liye password lazmi hai
                'username'  => 'nullable|string|max:50|unique:users,username',
                'phone_num' => 'nullable|string|max:20',
            ]);

            $data = [
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password), // Naya password hash karna zaroori hai
                'username'  => $request->username,
                'phone_num' => $request->phone_num,
            ];

            User::create($data);
            return response()->json(['success' => 'User Created Successfully']);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'username'  => $user->username,
            'phone_num' => $user->phone_num,
        ]);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        //  SPATIE : Database level par 'onDelete cascade'
        //  'model_has_roles' table se user ki entries delete ho jayengi.
        $user->delete();
        return response()->json(['success' => 'User Deleted Successfully']);
    }

    // GET USER ROLES: Assign Role Modal ke liye data mangwana
    public function getUserRoles($id)
    {
        $user = User::findOrFail($id);
        
        // Database mein available saare roles
        $allRoles = Role::all(); 
        
        // Is specific user ke paas pehle se kaunse roles hain (sirf unki IDs)
        $userRoles = $user->roles->pluck('id')->toArray();

        return response()->json([
            'user'      => $user,
            'allRoles'  => $allRoles,
            'userRoles' => $userRoles,
        ]);
    }

    // SYNC USER ROLES: Modal se aaye hue checkboxes ko save karna
    public function syncUserRoles(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // AJAX se array aayega, e.g., [1, 3]. Agar khali hai toh empty array []
            $roleIds = $request->roles ?? []; 

            // Database se wo Role models nikalo jo in IDs se match karte hain
            $roles = Role::whereIn('id', $roleIds)->get();
            
            // SPATIE: syncRoles() -> Yeh purane roles detach (remove) karega aur naye selected roles attach (add) karega.
            $user->syncRoles($roles);

            return response()->json(['success' => 'Roles assigned successfully']);
        } catch (\Exception $e) {
            \Log::error('Role Sync Error: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Failed to assign roles',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}