<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		$tableNames = config("permission.table_names");
		Schema::table($tableNames["permissions"], function (Blueprint $table) {
			$table
				->string("module")
				->nullable()
				->after("name");
			$table
				->text("description")
				->nullable()
				->after("module");
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		$tableNames = config("permission.table_names");
		Schema::table($tableNames["permissions"], function (Blueprint $table) {
			$table->dropColumn("description");
			$table->dropColumn("module");
		});
	}
};
