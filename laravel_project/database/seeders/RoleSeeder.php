<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Assuming Spatie package will be used
use Illuminate\Support\Facades\DB; // For direct DB insert if table exists but Spatie not yet installed

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the roles table exists, as Spatie migrations might not have run yet in the plan.
        // This is a simplified check.
        $roles_table_exists = false;
        try {
            // Attempt a simple query to see if the table is recognized.
            // This isn't a perfect check for table existence across all DBs supported by Laravel,
            // but good enough for this seeder's conditional logic.
            DB::table("roles")->first();
            $roles_table_exists = true;
        } catch (\Illuminate\Database\QueryException $e) {
            // Table likely doesn't exist or Spatie migrations haven't run
            echo "RoleSeeder: 'roles' table (expected by Spatie) not found or not accessible yet. Seeding directly to a generic roles table if available from user schema, or skipping if not.\n";
        }

        $default_roles = [
            ["name" => "admin", "guard_name" => "web"],
            ["name" => "student", "guard_name" => "web"],
            ["name" => "parent", "guard_name" => "web"],
            ["name" => "teacher", "guard_name" => "web"],
            ["name" => "accountant", "guard_name" => "web"],
            ["name" => "librarian", "guard_name" => "web"],
        ];

        if ($roles_table_exists && class_exists(Role::class)) {
            echo "RoleSeeder: Seeding roles using Spatie\Permission\Models\Role...\n";
            foreach ($default_roles as $role) {
                Role::firstOrCreate($role);
            }
        } else {
            // Fallback: if there's a `roles` table from the user schema (not Spatie's yet)
            // and it has a 'name' column, try to seed into it.
            // This is a guess based on the table name `roles` seen in the schema dump.
            $user_schema_roles_table_exists = false;
            try {
                DB::table("roles")->first(); // Check again, specifically for the generic one
                // Check if it has a 'name' column at least
                 $first_role_row = DB::table("roles")->first();
                 if ($first_role_row === null || property_exists($first_role_row, "name")) {
                    $user_schema_roles_table_exists = true;
                 } else {
                    echo "RoleSeeder: User defined 'roles' table exists but does not have a 'name' column as expected.\n";
                 }

            } catch (\Illuminate\Database\QueryException $e){
                 echo "RoleSeeder: User defined 'roles' table also not found. Skipping role seeding.\n";
            }

            if ($user_schema_roles_table_exists) {
                echo "RoleSeeder: Spatie Role model not available. Seeding directly to user-defined 'roles' table (if structure is compatible)...\n";
                foreach ($default_roles as $role_data) {
                    // Assuming user's 'roles' table has 'name', 'slug', 'is_system', 'is_superadmin'
                    // and 'created_at', 'updated_at' based on their schema for `roles` table.
                    // The 'slug' might be same as name, 'is_system'=0, 'is_superadmin'=0 for these basic roles.
                    // This is a very basic adaptation.
                    $slug = strtolower(str_replace(" ", "_", $role_data["name"]));
                    DB::table("roles")->updateOrInsert(
                        ["name" => $role_data["name"]],
                        [
                            "slug" => $slug, // Assuming slug is similar to name
                            "is_active" => 1, // Assuming 'is_active' field with 1 for active
                            "is_system" => ($role_data["name"] == "admin" ? 1: 0), // Example for system role
                            "is_superadmin" => ($role_data["name"] == "admin" ? 1: 0), // Example for superadmin
                            "created_at" => now(),
                            "updated_at" => now()
                        ]
                    );
                }
                echo "RoleSeeder: Direct seeding to user-defined 'roles' table attempted.\n";
            }
        }
    }
}
