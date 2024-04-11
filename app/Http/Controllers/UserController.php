<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $users = User::latest()->get();
        return $users;
    }
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()){
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'min:8|nullable',
        ]);
        $user -> update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        return $user;
    }
    public function destroy(User $user){
        $user->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function changeRole(User $user){
        $user->update([
           'role' =>  request('role')
        ]);
        return response()->json(['success' => true]);
    }

    public function search(){
        $querySearch = request('searchQuery');
        $user = User::where('name', 'like', "%{$querySearch}%")->get();

        return response()->json($user);
    }
    public function bulkDelete(){
        User::whereIn('id', request('ids'))->delete();
        return response()->json(['message' => 'Users deleted successfully!']);
    }
}
