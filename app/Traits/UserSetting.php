<?php

namespace Modules\UserManagement\Traits;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserManagement\Models\UserProfile;
use Modules\UserManagement\Models\SocialAccount;

trait UserSetting
{
	use HasRoles, SoftDeletes;

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
}
