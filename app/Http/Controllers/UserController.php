<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return $users;
    }
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'string|min:6',
        ]);

        if ($validator -> fails()){
            return response() -> json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator -> validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, User $user){
        $request -> validate([
            'email' => 'required|unique:users,email,'.$user->id,
        ]);
        $user -> update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        return $user;
    }
}
