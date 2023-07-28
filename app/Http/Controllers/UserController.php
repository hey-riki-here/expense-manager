<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(['id','name', 'email_address', 'role', 'created_at']);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = User::all(['id','name', 'email_address', 'password', 'role', 'created_at']);
        
        $emailAlreadyInUse = false;
        foreach ($users as $user) {
            if (strcmp($user->email_address, $request->email_address) == 0){
                $emailAlreadyInUse = true;
                break;
            }
        }

        if ($emailAlreadyInUse){
            return response()->json([
                'message' => 'Email already in use',
                'created' => false,
            ]);
        } else {
            $user = User::create($request->post());
            return response()->json([
                'message' => 'User created successfully!',
                'created' => true,
                'user' => $user,
            ]);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->fill($request->post())->save();
        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully!',
        ]);
    }
}
