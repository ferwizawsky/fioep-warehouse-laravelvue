<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $data = User::where("search", "like", "%" . $request->name . "%")->paginate($request->limit ?? 10);
        return UserResource::collection($data);
    }

    public function show($id)
    {
        $data = User::find($id);
        if ($data) {
            return new UserResource($data);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }



    public function roles()
    {
        $data = Role::get();
        return response()->json(["data" => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "username" => "required",
            "password" =>  [
                'required',
                'string',
                'min:6',             // must be at least 10 characters in length
                // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                // 'regex:/[0-9]/',      // must contain at least one digit
                // 'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);
        $username = strtolower($request->username);
        $tmp1 = User::where("username", "=", $username)->first();
        if ($tmp1) return response()->json(["message" => "Username Already Taken"], 409);
        $tmp = User::create([
            "username" => $username,
            "email" => $request->email ?? null,
            "name" => $request->name ?? null,
            "password" => $request->password,
            "role_id" => $request->role_id ?? 0,
        ]);
        return new UserResource($tmp);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            "username" => "required",
        ]);
        $tmp1 = User::find($id);
        if (!$tmp1) return response()->json(["message" => "Not Found"], 404);
        if ($request->password) {
            $tmp1->update([
                "password" => Hash::make($request->password),
            ]);
        }
        $tmp1->update([
            "username" => $request->username,
            "email" => $request->email ?? null,
            "name" => $request->name ?? null,
            "role_id" => $request->role_id ?? 0,
        ]);
        $tmp = User::find($id);
        return new UserResource($tmp);
    }


    public function delete($id)
    {
        $tmp1 = User::find($id);
        if (!$tmp1) return response()->json(["message" => "Not Found"], 404);
        $tmp1->delete();
        return new UserResource($tmp1);
    }
}
