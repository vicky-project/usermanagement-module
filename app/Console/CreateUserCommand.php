<?php

namespace Modules\Core\Console;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUserCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 */
	protected $signature = "app:create-user {email}";

	/**
	 * The console command description.
	 */
	protected $description = "Create new user.";

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$email = $this->argument("email");
		$username = $this->ask("What is the username");
		$password = $this->secret("What is the password?");
		$roles = $this->anticipate("Choice role", function (string $input) {
			return Permission::whereLike("name", "{$input}%")
				->pluck("name")
				->all();
		});
		dd($roles);

		$prepareData = [
			"name" => $username,
			"email" => $email,
			"password" => $password,
		];

		$this->table(
			["name", "email", "password", "roles"],
			[$username, $email, $password, $roles]
		);

		if (!$this->confirm("Is this okay?", true)) {
			$this->error("Canceled..");
			return;
		}
		try {
			$this->info("Creating user...");
			$user = User::firstOrCreate([
				"email" => $email,
				"name" => $username,
				"password" => Hash::make($password),
			]);

			$this->info("Assign user to roles..");
			$user->syncRoles($roles);
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}

	/**
	 * Prompt for missing input arguments using the returned questions.
	 *
	 * @return array<string, string>
	 */
	protected function promptForMissingArgumentsUsing(): array
	{
		return [
			"email" => "Input email of user.",
		];
	}
}
