# Install module
```
composer require vicky-project/usermanagement-module
```

# Activate module
After installation module, by default is not active yet.
You can activate this module using:
```
php artisan module:enable UserManagement
```

# Run migration and seeder
```
php artisan migrate
php artisan module:seed UserManagement
```
