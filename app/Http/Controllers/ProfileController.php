<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function user(Request $request) {
        return $request->user()->only(['name', 'email', 'role']);
    }
    public function update(User $user, Request $request){
        $validated = $request->validate([
           'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
        ]);
        $request->user()->update($validated);
        return response()->json(['message' => "Update user successfully"]);
    }
}
