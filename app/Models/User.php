<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];
    protected $guarded = [];

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
    ];

    public function privileges()
    {
        return $this->belongsTo('App\Models\UserPrivilege', 'privilege')->withTrashed();
    }

    public function branch_services()
    {
        return $this->belongsTo('App\Models\BranchService', 'branch_service')->withTrashed();
    }

    public function getThemeAttribute()
    {
        $theme = session('theme');
        if (!$theme) {
            $theme = 'light';
            session('theme', 'light');
        }
        return $theme;
    }

    public function getSetRepairAttribute()
    {
        return Job::where('status', Config::all()->first()->invoice_job_status_invoice)
            ->whereYear('collection_at', Carbon::now()->year)
            ->whereMonth('collection_at', Carbon::now()->month)
            ->get()
            ->count();
    }

    public function getSpeedRepairAttribute()
    {
        $jobs =  Job::where('status', Config::all()->first()->invoice_job_status_invoice)
            ->whereYear('collection_at', Carbon::now()->year)
            ->whereMonth('collection_at', Carbon::now()->month)
            ->get();

        $count = 0;
        foreach ($jobs as $row) {
            if ($row->day <= 2) {
                $count += 1;
            }
        }

        return $count;
    }

    public function getIncomeAttribute()
    {
        $jobs =  Job::where('status', Config::all()->first()->invoice_job_status_invoice)
            ->whereYear('collection_at', Carbon::now()->year)
            ->whereMonth('collection_at', Carbon::now()->month)
            ->get();

        $sum = 0;
        foreach ($jobs as $row) {
            $total = Invoice::where('job', $row->id)->get()->first()->total_income;
            if ($total > 0) {
                $sum += $total;
            }
        }

        return $total;
    }
}
