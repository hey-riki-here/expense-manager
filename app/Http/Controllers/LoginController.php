<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = User::all(['id','name', 'email_address', 'password', 'role', 'created_at']);

        $loggedIn = '';

        foreach ($users as $user) {
            if (strcmp($user->email_address,$request->email) == 0 && strcmp($user->password,$request->password) == 0){
                $loggedIn = $user;
                break;
            }
        }

        return $loggedIn;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return 'destroy';
    }
}
