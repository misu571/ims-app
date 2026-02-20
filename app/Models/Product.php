<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphOne, MorphMany};

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'products';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'cost',
        'reorder',
        'brand_id',
        'category_id',
        'product_type_id',
        'unit_id',
        'supplier_id',
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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->chaperone();
    }

    public function latestImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->latestOfMany('is_thumbnail');
    }

    public function oldestImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->oldestOfMany('is_thumbnail');
    }

    public function thumbnail(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->ofMany('is_thumbnail', 'MAX');
    }
}
