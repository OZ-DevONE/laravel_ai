<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\DeleteUserComments;
use App\Jobs\DeleteUserDislikes;
use App\Jobs\DeleteUserLikes;
use App\Jobs\DeleteUserPhotos;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed for user management in this context
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not needed for user management in this context
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Not needed for user management in this context
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Not needed for user management in this context
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Ban the specified user.
     */
    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = true;
        $user->save();

        BannedUser::create([
            'user_id' => $user->id,
            'ip_address' => $user->ip_address,
            'user_agent' => $user->user_agent
        ]);

        // Dispatch jobs to delete user-related data
        DeleteUserComments::dispatch($user);
        DeleteUserLikes::dispatch($user);
        DeleteUserDislikes::dispatch($user);
        DeleteUserPhotos::dispatch($user);

        return redirect()->route('admin.users.index')->with('success', 'User banned successfully.');
    }

    /**
     * Unban the specified user.
     */
    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = false;
        $user->save();

        BannedUser::where('user_id', $user->id)->delete();

        return redirect()->route('admin.users.index')->with('success', 'User unbanned successfully.');
    }
}