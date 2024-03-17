<?php

namespace App\Http\Controllers;

use App\Interface\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    private $user_service;

    public function __construct(UserServiceInterface $user_service)
    {
        $this->user_service = $user_service;
    }

    public function index()
    {
        $data['users'] = $this->user_service->getUsers(false);
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
            list($code, $message) = $this->user_service->createUser($request->all());
            $alert = $this->getAlert($code, $message);
        } catch (\Throwable $th) {
            $alert = $this->getAlert();
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
            list($code, $message) = $this->user_service->updateUser($request->all(), $request->id);
            $alert = $this->getAlert($code, $message);
        } catch (\Throwable $th) {
            $alert = $this->getAlert();
            $this->user_service->logForException($th, 'UPDATE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function trash($id)
    {
        try {
            list($code, $message) = $this->user_service->trashUser($id);
            $alert = $this->getAlert($code, $message);
        } catch (\Throwable $th) {
            $alert = $this->getAlert();
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
            list($code, $message) = $this->user_service->forceDeleteUser($id);
            $alert = $this->getAlert($code, $message);
        } catch (\Throwable $th) {
            $alert = $this->getAlert();
            $this->user_service->logForException($th, 'DELETE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    public function restore($id)
    {
        try {
            list($code, $message) = $this->user_service->restoreUser($id);
            $alert = $this->getAlert($code, $message);
        } catch (\Throwable $th) {
            $alert = $this->getAlert();
            $this->user_service->logForException($th, 'RESTORE_USER');
        }
        return redirect()->route('dashboard')->with('alert', $alert);
    }

    private function getAlert($code = 500, $message = 'Something went wrong')
    {
        $alert['message'] = $message;
        $alert['color'] = User::ALERT_ERROR;
        if (!empty($code)) {
            if ($code < 400) {
                $alert['color'] = User::ALERT_SUCCESS;
            }
        }

        return $alert;
    }
}
