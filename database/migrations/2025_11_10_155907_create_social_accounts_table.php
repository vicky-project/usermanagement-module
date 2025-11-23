<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create("social_accounts", function (Blueprint $table) {
			$table->id();
			$table
				->foreignId("user_id")
				->constrained()
				->onDelete("cascade");
			$table->string("provider"); // google, facebook, github, etc
			$table->string("provider_id");
			$table->text("token");
			$table->text("refresh_token")->nullable();
			$table->timestamp("expires_at")->nullable();
			$table->json("provider_data")->nullable();
			$table->timestamps();

			$table->unique(["provider", "provider_id"]);
			$table->index(["user_id", "provider"]);
		});
	}

	public function down()
	{
		Schema::dropIfExists("social_accounts");
	}
};
