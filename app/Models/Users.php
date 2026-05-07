<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Users extends Authenticatable
{
	use HasRoles;

    protected $table = 'users';
    protected $primaryKey = "id";

    protected $guard_name = 'web';
}
