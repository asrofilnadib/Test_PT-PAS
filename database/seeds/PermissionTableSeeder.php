<?php

  use Illuminate\Database\Seeder;
  use Spatie\Permission\Models\Permission;

  class PermissionTableSeeder extends Seeder
  {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // table of what user can do
      $permissions = [
        'dashboard',

        'user-list',
        'user-add',
        'user-edit',
        'user-delete',

        'barang-list',
        'barang-add',
        'barang-edit',
        'barang-delete',

        'transaksi_barang-list',
        'transaksi_barang-add',
        'transaksi_barang-edit',
        'transaksi_barang-delete',

        'profile-edit',
        'profile-delete',

        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
      ];

      foreach ($permissions as $permmission) {
        Permission::create(['name' => $permmission]);
      }
    }
  }
