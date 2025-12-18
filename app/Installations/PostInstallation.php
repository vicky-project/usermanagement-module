<?php

namespace Modules\UserManagement\Installations;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Artisan;

class PostInstallation
{
	public function handle(string $moduleName)
	{
		try {
			$module = Module::find($moduleName);
			$module->enable();

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
}
