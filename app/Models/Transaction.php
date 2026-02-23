<?php

namespace App\Models;

use App\Enums\{TransactionStatus, TransactionType};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trx_id',
        'trx_date',
        'trx_type',
        'total_item',
        'total_price',
        'status',
        'validate_by',
        'validate_at',
        'entry_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'trx_date' => 'datetime',
            'validate_at' => 'datetime',
            'trx_type' => TransactionType::class,
            'status' => TransactionStatus::class,
        ];
    }

    public function validateBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validate_by');
    }

    public function entryBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
