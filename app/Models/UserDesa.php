<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // ✅ import trait dari Sanctum
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ ganti base class
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\PengajuanSurat;


class UserDesa extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user_desa';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nik','nama','email','password','role_id','alamat','no_hp'];

    public $timestamps = false;


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Alias accessor untuk compatibility dengan middleware
    public function getRoleModelAttribute()
    {
        return $this->role;
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanSurat::class, 'nik_pemohon', 'nik');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Helper method to check if user has specific role
    public function hasRole($roleName)
    {
        return $this->role && $this->role->nama_role === $roleName;
    }

    // Helper method to check if user is admin
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
