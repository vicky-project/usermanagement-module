<?php

namespace Modules\UserManagement\Installations;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Artisan;
use Modules\UserManagement\Services\TraitInserter;

class PostInstallation
{
	public function handle(string $moduleName)
	{
		try {
			$module = Module::find($moduleName);
			$module->enable();

			$result = $this->insertTraitToUserModel();
			if ($result["success"] === false) {
				throw new \Exception($result["message"]);
			}

			Artisan::call("migrate", ["--force" => true]);
			Artisan::call("module:seed", [
				"--module" => $module->getName(),
				"--force" => true,
			]);
		} catch (\Exception $e) {
			logger()->error(
				"Failed to run post installation of user management: " .
					$e->getMessage()
			);

			throw $e;
		}
	}

	private function insertTraitToUserModel()
	{
		return TraitInserter::insertTrait(
			"Modules\UserManagement\Traits\UserSetting"
		);
	}
}
