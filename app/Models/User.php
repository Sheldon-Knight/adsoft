<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'surname',
        'gender',
        'phone',
        'email',
        'password',
        'address',
        'is_admin',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function instructions()
    {
        return $this->hasMany(Instruction::class, 'assigned_to');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function getTodaysAttendance()
    {
        $now = $this->freshTimestamp();

       return $this->attendances()
            ->where('day', $now->format('Y-m-d'))            
            ->first();
    }

    public function checkIn()
    {
        $now = $this->freshTimestamp();

        return $this->attendances()->create([
            'day' => $now->format('Y-m-d'),
            'time_in' => $now->format('H:i')
        ]);
    }

    public function checkOut()
    {
        $now = $this->freshTimestamp();

        return $this->attendances()
            ->where('day', $now->format('Y-m-d'))
            ->whereNull('time_out')
            ->firstOrFail()
            ->update([
                'time_out' => $now->format('H:i'),
            ]);
    }
}
