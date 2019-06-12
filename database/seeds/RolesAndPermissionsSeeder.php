<?php

    use Illuminate\Database\Seeder;
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\PermissionRegistrar;

    class RolesAndPermissionsSeeder extends Seeder
    {
        public function run(): void
        {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            Role::create(['name' => 'mod']);
            Role::create(['name' => 'admin']);
        }
    }