<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		// Cek jika tabel users belum ada, buat baru
		if (!Schema::hasTable("users")) {
			Schema::create("users", function (Blueprint $table) {
				$table->id();
				$table->string("name");
				$table->string("email")->unique();
				$table->timestamp("email_verified_at")->nullable();
				$table->string("password");
				$table->boolean("is_active")->default(true);
				$table->text("two_factor_secret")->nullable();
				$table->text("two_factor_recovery_codes")->nullable();
				$table->timestamp("two_factor_confirmed_at")->nullable();
				$table->rememberToken();
				$table->timestamps();
				$table->softDeletes();
			});
		} else {
			// Jika tabel sudah ada, tambahkan kolom yang diperlukan
			Schema::table("users", function (Blueprint $table) {
				if (!Schema::hasColumn("users", "is_active")) {
					$table
						->boolean("is_active")
						->default(true)
						->after("password");
				}
				if (!Schema::hasColumn("users", "deleted_at")) {
					$table->softDeletes();
				}
			});
		}
	}

	public function down()
	{
		// Hanya menghapus kolom tambahan, jangan hapus tabel users
		Schema::table("users", function (Blueprint $table) {
			$columns = ["is_active"];
			foreach ($columns as $column) {
				if (Schema::hasColumn("users", $column)) {
					$table->dropColumn($column);
				}
			}

			if (Schema::hasColumn("users", "deleted_at")) {
				$table->dropSoftDeletes();
			}
		});
	}
};
