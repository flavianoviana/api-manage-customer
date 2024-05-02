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
     * @return Customer
     */
    public static function createCustomer(array $fields): Customer
    {
        return self::create($fields);
    }

    /**
     * @param array $fields
     * @param int $customerId
     * @return bool
     */
    public static function updateCustomer(array $fields, int $customerId): bool
    {
        return self::where('id', '=', $customerId)->update($fields);
    }

    /**
     * @param int $customerId
     * @return bool
     */
    public static function deleteCustomer(int $customerId): bool
    {
        return self::where('id', '=', $customerId)->delete();
    }
}
