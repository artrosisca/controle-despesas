<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $user->update($request->only(['name', 'email', 'password']));

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function addFriend(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $friend = User::findOrFail($request->input('friend_id'));

        if ($user->isFriend($friend)) {
            return response()->json(['message' => 'Already friends'], 400);
        }

        $user->addFriend($friend);

        return response()->json(['message' => 'Friend added successfully'], 201);
    }

    public function removeFriend(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $friend = User::findOrFail($request->input('friend_id'));

        if (!$user->isFriend($friend)) {
            return response()->json(['message' => 'Not friends'], 400);
        }

        $user->removeFriend($friend);

        return response()->json(['message' => 'Friend removed successfully'], 200);
    }
}
