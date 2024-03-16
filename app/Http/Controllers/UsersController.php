<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function index()
    {
        $data['users'] = $this->user_service->getUsers();
        $data['heading'] = 'Dashboard';
        return view('users.dashboard', $data);
    }

    public function edit($id)
    {
        $data['heading'] = 'Edit User';

        try {
            $data['user'] = $this->user_service->findUser($id);
        } catch (\Throwable $th) {
            $this->user_service->logForException($th, 'EDIT_USER');
        }
        return view('users.edit', $data);
    }

    public function show($id)
    {
        $data['heading'] = 'User Details';

        try {
            $data['user'] = $this->user_service->findUser($id);
        } catch (\Throwable $th) {
            $this->user_service->logForException($th, 'USER_DETAILS');
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
            $alert = $this->user_service->createUser($request->all());
        } catch (\Throwable $th) {
            $alert = $this->user_service->unknownErrorAlert();
            $this->user_service->logForException($th, 'STORE_USER');
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
            $alert = $this->user_service->updateUser($request->all(), $request->id);
        } catch (\Throwable $th) {
            $alert = $this->user_service->unknownErrorAlert();
            $this->user_service->logForException($th, 'UPDATE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function trash($id)
    {
        try {
            $alert = $this->user_service->trashUser($id);
        } catch (\Throwable $th) {
            $alert = $this->user_service->unknownErrorAlert();
            $this->user_service->logForException($th, 'TRASH_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function trashList()
    {
        $data['heading'] = 'Trashed Users';
        try {
            $data['trashed'] = $this->user_service->getTrashedUsers();
        } catch (\Throwable $th) {
            $this->user_service->logForException($th, 'TRASH_LIST');
        }
        return view('users.trashed', $data);
    }

    public function delete($id)
    {
        try {
            $alert = $this->user_service->forceDeleteUser($id);
        } catch (\Throwable $th) {
            $alert = $this->user_service->unknownErrorAlert();
            $this->user_service->logForException($th, 'DELETE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function restore($id)
    {
        try {
            $alert = $this->user_service->restoreUser($id);
        } catch (\Throwable $th) {
            $alert = $this->user_service->unknownErrorAlert();
            $this->user_service->logForException($th, 'RESTORE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }
}
