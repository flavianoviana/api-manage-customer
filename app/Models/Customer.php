<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
    ];

    /**
     * @param array $fields
     * @return Customer|null
     */
    public static function createCustomer(array $fields): Customer
    {
        return self::create($fields);
    }
}
