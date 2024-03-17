<?php

namespace App\Interface;

interface UserServiceInterface
{
    public function getUsers($withSelf = true);

    public function findUser($id);

    public function createUser($request);

    public function prepareStoreData($request);

    public function updateUser($request, $id);

    public function trashUser($id);

    public function getTrashedUsers();

    public function findTrashedUser($id);

    public function forceDeleteUser($id);

    public function restoreUser($id);

    public function logForException($th, $action = 'action');

    public function unknownError();

}
