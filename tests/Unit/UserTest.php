<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Services\UserService;
use Str;

class UserTest extends TestCase
{
    public function test_create_user()
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $response = (new UserService())->createUser($data);
        $this->assertSame(200, $response[0]);
    }

    public function test_get_users()
    {
        $totalUsers = count((new UserService())->getUsers());

        if ($totalUsers > 0) {
            $this->assertNotEmpty($totalUsers);
        } else {
            $this->assertEmpty($totalUsers);
        }
    }

    public function test_update_user()
    {
        $user = $this->createUser();
        $updatedData = [
            'name' => 'updated name',
            'email' => $user->email
        ];
        $response = (new UserService())->updateUser($updatedData, $user->id);
        $this->assertSame(200, $response[0]);
    }

    private function createUser()
    {
        return User::factory()->create();
    }

    public function test_trash_user()
    {
        list($user, $response) = $this->trashUser();
        $this->assertSame(200, $response[0]);
    }

    private function trashUser()
    {
        $user = $this->createUser();
        $response = (new UserService())->trashUser($user->id);
        return [$user, $response];

    }

    public function test_restore_user()
    {
        list($user, $response) = $this->trashUser();
        $response = (new UserService())->restoreUser($user->id);
        $this->assertSame(200, $response[0]);

    }

    public function test_force_delete_user()
    {
        list($user, $response) = $this->trashUser();
        $response = (new UserService())->forceDeleteUser($user->id);
        $this->assertSame(200, $response[0]);
    }

}
