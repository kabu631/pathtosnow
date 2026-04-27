<?php
// app/Models/User.php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name','email','password','role','phone','avatar','nationality'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function bookings()         { return $this->hasMany(Booking::class); }
    public function posts()            { return $this->hasMany(Post::class); }
    public function orders()           { return $this->hasMany(Order::class); }
}
