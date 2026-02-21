<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'stock',
        'stock_value',
        'can_reorder',
    ];

    protected function casts(): array
    {
        return [
            'can_reorder' => 'boolean',
        ];
    }

    #[Scope]
    protected function reorder(Builder $query, bool $is = true): void
    {
        $query->where('can_reorder', $is);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
