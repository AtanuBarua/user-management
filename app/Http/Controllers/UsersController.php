<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $data['users'] = User::where('id', '!=', auth()->id())->paginate(10);
        $data['heading'] = 'Dashboard';
        return view('users.dashboard', $data);
    }

    public function edit($id)
    {
        $data['heading'] = 'Edit User';

        try {
            $data['user'] = User::find($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return view('users.edit', $data);
    }

    public function show($id)
    {
        $data['heading'] = 'User Details';

        try {
            $data['user'] = User::find($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return view('users.details', $data);
    }

    public function create()
    {
        $data['heading'] = 'Create User';
        return view('users.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|' . Rule::unique('users')->ignore($request->id),
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($user) {
                $alert['message'] = "User created successfully";
                $alert['color'] = 'green';
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $alert['message'] = "Something went wrong";
            $alert['color'] = 'red';
        }

        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|' . Rule::unique('users')->ignore($request->id)
        ]);

        try {
            $user = User::find($request->id);
            if (!empty($user)) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);
            }
            $alert['message'] = "User updated successfully";
            $alert['color'] = 'green';
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $alert['message'] = "Something went wrong";
            $alert['color'] = 'red';
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function trash($id)
    {
        try {
            $user = User::find($id);
            $alert['message'] = "User not found";
            $alert['color'] = 'red';

            if (!empty($user)) {
                $user->delete();
                $alert['message'] = "User moved to trash successfully";
                $alert['color'] = 'green';
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $alert['message'] = "Something went wrong";
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function trashList()
    {
        $data['heading'] = 'Trashed Users';
        try {
            $data['trashed'] = User::onlyTrashed()->paginate(10);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return view('users.trashed', $data);
    }

    public function delete($id)
    {
        try {
            $user = User::withTrashed()->find($id);
            $alert['message'] = "User not found";
            $alert['color'] = 'red';

            if (!empty($user)) {
                $user->forceDelete();
                $alert['message'] = "User deleted successfully";
                $alert['color'] = 'green';
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $alert['message'] = "Something went wrong";
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function restore($id)
    {
        try {
            $alert['message'] = "User not found";
            $alert['color'] = 'red';

            $user = User::withTrashed()->find($id);
            if (!empty($user)) {
                $user->restore();
                $alert['message'] = "User restored successfully";
                $alert['color'] = 'green';
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $alert['message'] = "Something went wrong";
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }
}
