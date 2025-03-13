<?php

namespace App\Models;

use App\Enums\FlightStatusEnum;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flight extends Model
{
    /** @use HasFactory<\Database\Factories\FlightFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'destination',
        'departune_date',
        'return_date',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => FlightStatusEnum::REQUESTED->value,
    ];

    public function isApproved(): bool
    {
        return $this->status === FlightStatusEnum::APPROVED->value;
    }

    public function isCancelled(): bool
    {
        return $this->status === FlightStatusEnum::CANCELED->value;
    }

    public function isRequested(): bool
    {
        return $this->status === FlightStatusEnum::REQUESTED->value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include flights of a given user.
     */
    public function scopeOfUser(Builder $builder, int $userId): void
    {
        $builder->where('user_id', $userId);
    }

    /**
     * Scope a query to only include flight of a given status.
     */
    public function scopeOfStatus(Builder $builder, string $status): void
    {
        $_status = FlightStatusEnum::tryFrom($status);

        $builder->where('status', $_status->value);
    }

    /**
     * Scope a query to only include flight of a range flight dates.
     */
    public function scopeOfPeriod(Builder $builder, ?DateTime $startDate = null, ?DateTime $endDate = null): void
    {
        $builder->when(
            value: $startDate,
            callback: fn($builder, $startDate) => $builder
                ->whereDate('departune_date', '>=', $startDate->format('Y-m-d'))
        );

        $builder->when(
            value: $endDate,
            callback: fn($builder, $endDate) => $builder
                ->whereDate('return_date', '<=', $endDate->format('Y-m-d'))
        );
    }
}
