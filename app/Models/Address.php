<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createOrUpdateAddress($userId, $addresses)
    {
        self::where('user_id', $userId)->delete();

        if (count($addresses) > 0) {
            foreach ($addresses as $key => $value) {
                (new Address())->create([
                    'user_id' => $userId,
                    'address' => $value
                ]);
            }
        }
    }
}
