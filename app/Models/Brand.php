<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    protected $table = 'brands';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'entry_by',
    ];

    protected static function booted(): void
    {
        static::saving(function ($query) {
            $query->entry_by = auth('web')->id();
        });
    }

    public function entryBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entry_by');
    }
}
