<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::get();
        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::get();
        return view('admin.users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'email' => 'email|required|unique:users',
            'name_ar' => 'required',
            'name_en' => 'required',
            'password' => 'required',
        ]);

        $user = new User;
        $user->name = $request->name_ar;
        $user->name_ar = $request->name_ar;
        $user->name_en = $request->name_en;
        $user->address = $request->address;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->mobile = $request->mobile ? $request->mobile : "";


        $user->save();
        $user->syncRoles([$request->user_group]);

        return redirect()->route('users.index')->with('success', 'success')->with('id', $user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        return view('admin.users.show', compact('user', 'roles'));
    }


    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();

        return view('admin.users.edit', compact('user', 'roles'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'email' => 'required|unique:users,email,' . $id,
        ]);
        $user = User::find($id);
        $user->name = $request->name_ar;
        $user->name_ar = $request->name_ar;
        $user->name_en = $request->name_en;
        $user->address = $request->address;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->mobile = $request->mobile ? $request->mobile : "";



        $user->save();
        $user->syncRoles([$request->user_group]);

        return redirect()->route('users.index')->with('success', 'success')->with('id', $user->id);
    }


    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'success');
    }

    public function showProfile()
    {
        return view('admin.users.profile');
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users,email,' . auth()->user()->id,
        ]);
        $user = User::find(auth()->user()->id);
        $user->name = $request->name_ar;
        $user->name_ar = $request->name_ar;
        $user->name_en = $request->name_en;
        $user->address = $request->address;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->mobile = $request->mobile ? $request->mobile : "";


        $user->save();

        return redirect()->back()->with('success', 'success')->with('id', $user->id);
    }

    public function active($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->active = 1;
            $user->save();
        }
        return back()->with('success', 'User Activated');
    }


    public function deActive($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->active = 0;
            $user->save();
        }
        return back()->with('success', 'User Disabled');
    }
}
