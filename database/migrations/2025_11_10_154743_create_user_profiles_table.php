<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create("user_profiles", function (Blueprint $table) {
			$table->id();
			$table
				->foreignId("user_id")
				->constrained()
				->onDelete("cascade");
			$table->string("phone")->nullable();
			$table->text("address")->nullable();
			$table->string("city")->nullable();
			$table->string("state")->nullable();
			$table
				->string("country")
				->nullable()
				->default("Indonesia");
			$table->string("postal_code")->nullable();
			$table->date("date_of_birth")->nullable();
			$table->string("gender")->nullable();
			$table->text("bio")->nullable();
			$table->string("avatar")->nullable();
			$table->json("configuration")->nullable();
			$table->json("preferences")->nullable();
			$table->timestamps();

			$table->unique("user_id");
		});
	}

	public function down()
	{
		Schema::dropIfExists("user_profiles");
	}
};
