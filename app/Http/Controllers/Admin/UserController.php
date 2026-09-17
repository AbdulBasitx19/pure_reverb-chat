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



        
}
    

