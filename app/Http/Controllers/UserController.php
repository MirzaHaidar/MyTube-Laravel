<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signup(Request $request) {
    try {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save(); 

        return response()->json([
            "message" => "User registered"
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            "message" => "Validation failed",
            "errors" => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            "message" => "Server error",$e
        ], 500);
    }
}

public function login(Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            // error_log($user);
            
             $token = $user->createToken('kuchbhi')->plainTextToken;
            //  error_log($token);
            return response(['token'=>$token,
                "message" => "User logged into website"
            ]);
        } else {
            return response()->json([
                "message" => "Login failed"
            ]);
        }
    } catch (ValidationException $e) {
        return response()->json([
            "message" => "Validation failed",
            "errors" => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            "message" => "Server error",$e
        ], 500);
    }
}

public function updateInformation(Request $request)
{
    $name = $request->input('name');
    $password = $request->input('password');
    $user_ID = Auth::user()->id;
    
    try {
        $user = User::find($user_ID);

        if ($user) {
            $user->name = $name;
            $user->password = Hash::make($password);

            $user->save();

            return response()->json(['success' => true, 'message' => 'User information updated']);
        } else {
            return response()->json(['error' => 'User not found', $e], 404);
        }
    } catch (\Exception $e) {

        return response()->json(['error' => 'Failed to update user information', $e], 500);
    }
}

}
