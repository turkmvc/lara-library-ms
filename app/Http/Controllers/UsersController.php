<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->with('role')->get();

        return view('users.index', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
          'name'      => 'required|string|max:255',
          'email'     => 'required|string|email|max:255|unique:users',
          'password'  => 'required|string|min:6',
          'role_id'   => 'required|numeric',
          'status'    => 'required|boolean'
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role_id'   => $request->role_id,
            'status'    => $request->status
        ]);

        return back()->with('success', 'User added successfully.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
          'name'      => 'required|string|max:255',
          'email'     => 'required|string|email|max:255',
          'role_id'   => 'required|numeric',
          'status'    => 'required|boolean'
        ]);

        $user = User::findOrFail($id);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->role_id  = $request->role_id;
        $user->status   = $request->status;
        $user->save();

        return back()->with('success', 'User updated successfully.');
    }


    public function destroy($id)
    {
      $user = User::findOrFail($id);
      $user->delete();

      return response()->json(['user' => 'deleted']);
    }

}
