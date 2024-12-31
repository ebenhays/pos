<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $casts = ['send_sms' => 'boolean'];

    protected $fillable = ['name', 'phone_no', 'send_sms', 'country_code'];
}
