<?php

  use App\Models\User;
  use Illuminate\Database\Seeder;
  use Illuminate\Support\Facades\Hash;
  use Spatie\Permission\Models\Permission;
  use Spatie\Permission\Models\Role;

  class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run(): void
  {
    $admin = User::create([
      'id' => 1,
      'name' => 'admin',
      'email' => 'admin@admin.com',
      'password' => Hash::make('admin123'),
    ]);

    $user = User::create([
      'id' => 2,
      'name' => 'user',
      'email' => 'user@user.com',
      'password' => Hash::make('qwe123'),
    ]);

    $superRole = Role::firstOrCreate(['name' => 'Admin']);
    $basicRole = Role::firstOrCreate(['name' => 'User']);

    $permission = Permission::pluck('id', 'id')->all();

    $superRole->syncPermissions($permission);
    $basicRole->syncPermissions([1, 6, 7, 10, 11]);

    $admin->assignRole([$superRole->id]);
    $user->assignRole([$basicRole->id]);
  }
}
