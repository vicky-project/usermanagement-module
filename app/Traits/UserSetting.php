<?php

namespace Modules\UserManagement\Traits;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Modules\UserManagement\Models\UserProfile;
use Modules\UserManagement\Models\SocialAccount;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

trait UserSetting
{
	use HasRoles, CausesActivity, LogsActivity, AuthenticationLoggable;

	/**
	 * Activity Log Options
	 */
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly(["name", "email", "is_active"])
			->logOnlyDirty()
			->dontSubmitEmptyLogs()
			->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}")
			->useLogName("users");
	}
	/**
	 * One-to-One: User has one Profile
	 */
	public function profile()
	{
		return $this->hasOne(UserProfile::class);
	}

	/**
	 * One-to-Many: User has many Social Accounts
	 */
	public function socialAccounts()
	{
		return $this->hasMany(SocialAccount::class);
	}

	/**
	 * One-to-Many: User has many Activities (via Spatie Activitylog)
	 */
	public function activities()
	{
		return $this->hasMany(
			\Spatie\Activitylog\Models\Activity::class,
			"causer_id"
		);
	}

	/**
	 * Custom Methods
	 */
	public function getFullAddressAttribute()
	{
		if (!$this->profile) {
			return null;
		}

		$addressParts = array_filter([
			$this->profile->address,
			$this->profile->city,
			$this->profile->state,
			$this->profile->postal_code,
			$this->profile->country,
		]);

		return implode(", ", $addressParts);
	}

	public function getInitialsAttribute()
	{
		$names = explode(" ", $this->name);
		$initials = "";

		foreach ($names as $name) {
			$initials .= strtoupper(substr($name, 0, 1));
		}

		return substr($initials, 0, 2);
	}

	public function getLastActivityAttribute()
	{
		return $this->activities()
			->latest()
			->first();
	}
}
