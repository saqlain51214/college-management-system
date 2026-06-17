<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldSync extends Command
{
    protected $signature   = 'shield:sync';
    protected $description = 'Generate permissions for all resources and sync them to super_admin role';

    public function handle(): void
    {
        $this->info('Step 1 — Generating permissions for all resources...');
        $this->call('shield:generate', ['--all' => true, '--panel' => 'admin']);

        $this->info('Step 2 — Syncing ALL permissions to super_admin role...');
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $all  = Permission::where('guard_name', 'web')->get();
        $role->syncPermissions($all);
        $this->line("  super_admin now has {$all->count()} permissions.");

        $this->info('Step 3 — Assigning super_admin role to user ID 1 (if needed)...');
        $user = \App\Models\User::find(1);
        if ($user && ! $user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
            $this->line("  super_admin role assigned to {$user->email}");
        } elseif ($user) {
            $this->line("  {$user->email} already has super_admin role.");
        }

        $this->info('Step 4 — Clearing permission cache...');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->call('cache:clear');
        $this->call('view:clear');

        $this->info('');
        $this->info('Done! Logout and login again in the browser to apply changes.');
    }
}
