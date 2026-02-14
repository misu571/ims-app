<?php

namespace App\Models;

use App\Enums\Gender;
use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'gender',
        'is_active',
        'entry_by',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'gender' => Gender::class,
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($query) {
            $query->entry_by = auth('web')->id();
        });
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->image ?? (new UiAvatarsProvider)->get($this);
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value == null ? $value : strtolower($value),
        );
    }

    #[Scope]
    protected function active(Builder $query, bool $is = true): void
    {
        $query->where('is_active', $is);
    }

    public function entryBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entry_by');
    }
}
