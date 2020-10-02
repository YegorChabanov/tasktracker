<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegistrationRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return UserResource::collection($users);
    }

    public function show($id)
    {
        if ($id != Auth::id()) {
            return response()->json(['message' => "You can not see info of other user"], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => "User $id not found"], 404);
        }

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        if ($id != Auth::id()) {
            return response()->json(['message' => "You can not edit profile of other user"], 403);
        }

        $user  = User::find($id);

        if (!$user) {
            return response()->json(['message' => "User $id not found"], 404);
        }

        $request->validate([
            'email' => 'required|email|unique:users' . ($id ? ",id,$id" : ''),
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
        ]);

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->save();

        return new UserResource($user);
    }

    public function destroy($id)
    {
        if ($id != Auth::id()) {
            return response()->json(['message' => "You can not delete profile of other user"], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => "User $id not found"], 404);
        }

        $user->delete();

        return response()->json(['message' => "You profile was deleted successfully"], 201);
    }
}
