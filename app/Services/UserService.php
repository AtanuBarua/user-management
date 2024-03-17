<?php

namespace App\Services;

use App\Interface\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceInterface
{
    private $status_code;
    private $status_message;

    public function getUsers($withSelf = true)
    {
        if ($withSelf) {
            $users = User::paginate(10);
        } else {
            $users = User::where('id', '!=', auth()->id())->paginate(10);
        }

        return $users;
    }

    public function findUser($id)
    {
        return User::findOrFail($id);
    }

    public function createUser($request)
    {
        $data = $this->prepareStoreData($request);
        $status = User::create($data);
        if ($status) {
            $this->status_code = 200;
            $this->status_message = 'User created successfully';
        } else {
            $this->unknownError();
        }
        return [$this->status_code, $this->status_message];
    }

    public function prepareStoreData($request)
    {
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['password'] = Hash::make($request['password']);
        return $data;
    }

    public function logForException($th, $action = 'action')
    {
        return Log::error([
            'action' => $action,
            'file' => $th->getFile(),
            'line' => $th->getLine(),
            'message' => $th->getMessage()
        ]);
    }

    public function updateUser($request, $id)
    {
        $user = $this->findUser($id);

        $status = $user->update([
            'name' => $request['name'],
            'email' => $request['email']
        ]);

        if ($status) {
            $this->status_code = 200;
            $this->status_message = 'User updated successfully';
        } else {
            $this->unknownError();
        }

        return [$this->status_code, $this->status_message];
    }

    public function trashUser($id)
    {
        $user = $this->findUser($id);
        $status = $user->delete();
        if ($status) {
            $this->status_code = 200;
            $this->status_message = 'User moved to trash successfully';
        } else {
            $this->unknownError();
        }
        return [$this->status_code, $this->status_message];
    }

    public function getTrashedUsers()
    {
        return User::onlyTrashed()->paginate(10);
    }

    public function findTrashedUser($id)
    {
        return User::onlyTrashed()->findOrFail($id);
    }

    public function forceDeleteUser($id)
    {
        $user = $this->findTrashedUser($id);
        $status = $user->forceDelete();
        if ($status) {
            $this->status_code = 200;
            $this->status_message = 'User deleted successfully';
        } else {
            $this->unknownError();
        }
        return [$this->status_code, $this->status_message];
    }

    public function restoreUser($id)
    {
        $user = $this->findTrashedUser($id);
        $status = $user->restore();
        if ($status) {
            $this->status_code = 200;
            $this->status_message = 'User restored successfully';
        } else {
            $this->unknownError();
        }
        return [$this->status_code, $this->status_message];
    }

    public function unknownError()
    {
        $this->status_code = 500;
        $this->status_message = 'Something went wrong';
    }
}
