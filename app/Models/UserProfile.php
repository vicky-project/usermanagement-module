<?php

namespace Modules\UserManagement\Models;

use App\Models\User;
use Laravolt\Avatar\Avatar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserProfile extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		"user_id",
		"phone",
		"address",
		"city",
		"state",
		"country",
		"postal_code",
		"date_of_birth",
		"gender",
		"bio",
		"avatar",
		"preferences",
	];

	protected $casts = [
		"date_of_birth" => "date",
		"preferences" => "array",
	];

	/**
	 * Activity Log Options
	 */
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly(["phone", "address", "city", "date_of_birth"])
			->logOnlyDirty()
			->setDescriptionForEvent(
				fn(string $eventName) => "User profile {$eventName}"
			)
			->useLogName("user-profiles");
	}

	/**
	 * One-to-One: Profile belongs to User
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Accessors & Mutators
	 */
	public function getAgeAttribute()
	{
		return $this->date_of_birth?->age;
	}

	public function getFormattedPhoneAttribute()
	{
		if (!$this->phone) {
			return null;
		}

		// Format phone number (contoh sederhana)
		return preg_replace("/(\d{3})(\d{4})(\d{4})/", '$1-$2-$3', $this->phone);
	}

	public function scopeImage()
	{
		return $this->avatar ??
			(new Avatar())
				->create($this->user()->name ?? auth()->user()->name)
				->setDimension(160, 160)
				->setFontSize(72)
				->setTheme("colorful")
				->setBackground("#001122")
				->setForeground("#129009")
				->toBase64();
	}
}
