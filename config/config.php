<?php

return [
	"name" => "UserManagement",

	"roles" => [
		"super-admin" => [
			"name" => "Super Admin",
			"permissions" => ["*"],
		],
		"admin" => [
			"name" => "Administrator",
			"permissions" => [
				"usermanagement.users.view",
				"usermanagement.users.create",
				"usermanagement.users.edit",
				"usermanagement.users.delete",
				"usermanagement.roles.view",
				"usermanagement.roles.create",
				"usermanagement.roles.edit",
				"usermanagement.roles.delete",
				"usermanagement.permissions.view",
				"usermanagement.permissions.edit",
			],
		],
		"user" => [
			"name" => "User",
			"permissions" => [],
		],
	],

	"pagination" => [
		"users" => 15,
		"roles" => 15,
		"permissions" => 15,
	],
];
