<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getUsers($withoutSelf = true)
    {
        if ($withoutSelf) {
            $users = User::where('id', '!=', auth()->id())->paginate(10);
        } else {
            $users = User::paginate(10);
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
            $alert = $this->storeUserSuccessAlert();
        } else {
            $alert = $this->unknownErrorAlert();
        }
        return $alert;
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
            $alert = $this->updateUserSuccessAlert();
        } else {
            $alert = $this->unknownErrorAlert();
        }

        return $alert;
    }

    public function trashUser($id)
    {
        $user = $this->findTrashedUser($id);
        $status = $user->delete();
        if ($status) {
            $alert = $this->trashUserSuccessAlert();
        } else {
            $alert = $this->unknownErrorAlert();
        }
        return $alert;
    }

    public function getTrashedUsers()
    {
        return User::onlyTrashed()->paginate(10);
    }

    public function findTrashedUser($id)
    {
        return User::withTrashed()->findOrFail($id);
    }

    public function forceDeleteUser($id)
    {
        $user = $this->findTrashedUser($id);
        $status = $user->forceDelete();
        if ($status) {
            $alert = $this->deleteUserSuccessAlert();
        } else {
            $alert = $this->unknownErrorAlert();
        }
        return $alert;
    }

    public function restoreUser($id)
    {
        $user = $this->findTrashedUser($id);
        $status = $user->restore();
        if ($status) {
            $alert = $this->deleteUserSuccessAlert();
        } else {
            $alert = $this->unknownErrorAlert();
        }
        return $alert;
    }

    public function unknownErrorAlert()
    {
        $alert['message'] = "Something went wrong";
        $alert['color'] = User::ALERT_ERROR;
        return $alert;
    }

    public function storeUserSuccessAlert()
    {
        $alert['message'] = "User created successfully";
        $alert['color'] = User::ALERT_SUCCESS;
        return $alert;
    }

    public function updateUserSuccessAlert()
    {
        $alert['message'] = "User updated successfully";
        $alert['color'] = User::ALERT_SUCCESS;
        return $alert;
    }

    public function trashUserSuccessAlert()
    {
        $alert['message'] = "User moved to trash successfully";
        $alert['color'] = User::ALERT_SUCCESS;
        return $alert;
    }

    public function deleteUserSuccessAlert()
    {
        $alert['message'] = "User deleted successfully";
        $alert['color'] = User::ALERT_SUCCESS;
        return $alert;
    }

    public function restoreUserSuccessAlert()
    {
        $alert['message'] = "User restored successfully";
        $alert['color'] = User::ALERT_SUCCESS;
        return $alert;
    }
}
