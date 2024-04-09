<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function user(Request $request) {
        return $request->user()->only(['name', 'email', 'role', 'avatar']);
    }
    public function update(User $user, Request $request){
        $validated = $request->validate([
           'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
        ]);
        $request->user()->update($validated);
        return response()->json(['message' => "Update user successfully"]);
    }
    public function uploadImage (Request $request){
        if($request->hasFile('profile_picture')){
            $link = Storage::put('/photos', $request->file('profile_picture'));
            $request->user()->update(['avatar' => $link]);
        }
        return response()->json(['message' => "Update picture successfully"]);
    }
    public function changePassword (Request $request, UpdateUserPassword $updater){
        $updater->update(
            auth()->user(),
            [
                'current_password' => $request->currentPassword,
                'password' => $request->password,
                'password_confirmation' => $request->passwordConfirmation
            ]
        );
        return response()->json(['message' => "Password changed successfully"]);
    }
}
