<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category_id',
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

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(self::class, 'category_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(self::class, 'category_id');
    }
}
