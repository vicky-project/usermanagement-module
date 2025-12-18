<?php

namespace Modules\UserManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SocialAccount extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		"user_id",
		"provider",
		"provider_id",
		"token",
		"refresh_token",
		"expires_at",
		"provider_data",
	];

	protected $casts = [
		"expires_at" => "datetime",
		"provider_data" => "array",
	];

	/**
	 * Activity Log Options
	 */
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly(["provider", "provider_id"])
			->logOnlyDirty()
			->setDescriptionForEvent(
				fn(string $eventName) => "Social account {$eventName}"
			)
			->useLogName("social-accounts");
	}

	/**
	 * Many-to-One: SocialAccount belongs to User
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Scope Methods
	 */
	public function scopeProvider($query, $provider)
	{
		return $query->where("provider", $provider);
	}

	/**
	 * Accessors
	 */
	public function getIsExpiredAttribute()
	{
		return $this->expires_at && $this->expires_at->isPast();
	}

	public function getProviderDataAttribute($value)
	{
		return json_decode($value, true) ?? [];
	}
}
