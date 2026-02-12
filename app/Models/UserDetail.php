<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use App\Enums\Religion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    protected $table = 'user_details';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id',
        'gender',
        'dob',
        'religion',
        'blood_group',
        'address',
        'city',
        'postcode',
        'nid_reg_id',
        'nid_reg_verified_at',
        'dob_reg_id',
        'dob_reg_verified_at',
        'doj',
        'employment_type',
        'designation_id',
        'department_id',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'datetime',
            'doj' => 'datetime',
            'gender' => Gender::class,
            'religion' => Religion::class,
            'blood_group' => BloodGroup::class,
            'employment_type' => EmploymentType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
