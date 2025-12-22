<?php

namespace Modules\UserManagement\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Spatie\Permission\PermissionServiceProvider;
use Modules\UserManagement\Services\PermissionRegistry;

class UserManagementServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . "/../../config/config.php",
			"usermanagement"
		);

		$this->app->register(PermissionServiceProvider::class);

		$this->app->singleton(PermissionRegistry::class, function () {
			return new PermissionRegistry();
		});
	}

	public function boot()
	{
		$this->registerCommands();
		$this->registerViews();
		$this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
		$this->loadRoutesFrom(__DIR__ . "/../../routes/web.php");

		// Register middleware
		$this->app["router"]->aliasMiddleware(
			"permission",
			\Spatie\Permission\Middleware\PermissionMiddleware::class
		);
		$this->app["router"]->aliasMiddleware(
			"role",
			\Spatie\Permission\Middleware\RoleMiddleware::class
		);
		$this->app["router"]->aliasMiddleware(
			"role_or_permission",
			\Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class
		);

		$this->app->booted(function () {
			if (app()->runningInConsole()) {
				return;
			}

			$registry = app(PermissionRegistry::class);
			$registry->syncAllPermissions();
			app()[
				\Spatie\Permission\PermissionRegistrar::class
			]->forgetCachedPermissions();
		});

		Paginator::useBootstrapFive();
	}

	protected function registerViews()
	{
		$this->loadViewsFrom(__DIR__ . "/../../resources/views", "usermanagement");
	}

	/**
	 * Register commands in the format of Command::class
	 */
	protected function registerCommands(): void
	{
		$this->commands([\Modules\UserManagement\Console\CreateUserCommand::class]);
	}
}
