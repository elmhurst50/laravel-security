<?php namespace SamJoyce777\LaravelSecurity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class LoginFail extends Model
{
    protected $guarded = ['id'];

    protected $table = 'login_fails';
}
