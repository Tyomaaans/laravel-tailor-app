<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /** @var array<int, string> */
    private array $resources = [
        'customers',
        'measurements',
        'orders',
        'order_items',
        'products',
        'categories',
        'payments',
        'production_tasks',
        'users',
        'suppliers',
        'material_stocks',
        'material_categories',
    ];

    /** @var array<int, string> */
    private array $sensitiveResources = [
        'users',
        'payments',
        'customers',
    ];

    /** @var array<int, string> */
    private array $operationalResources = [
        'measurements',
        'orders',
        'order_items',
        'products',
        'categories',
        'production_tasks',
        'suppliers',
        'material_stocks',
        'material_categories',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [];

        foreach ($this->resources as $resource) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                $permission = Permission::query()->firstOrCreate([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'api',
                ]);
                $permissions[$resource][$action] = $permission;
            }
        }

        $admin      = Role::query()->firstOrCreate(['name' => 'admin',      'guard_name' => 'api']);
        $sales      = Role::query()->firstOrCreate(['name' => 'sales',      'guard_name' => 'api']);
        $tailor     = Role::query()->firstOrCreate(['name' => 'tailor',     'guard_name' => 'api']);
        $production = Role::query()->firstOrCreate(['name' => 'production', 'guard_name' => 'api']);
        $manager    = Role::query()->firstOrCreate(['name' => 'manager',    'guard_name' => 'api']);

        // Admin: semua permission kecuali delete pada sensitive resources
        $adminPermissions = [];
        foreach ($this->resources as $resource) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                if ($action === 'delete' && in_array($resource, $this->sensitiveResources, true)) {
                    continue;
                }
                $adminPermissions[] = $permissions[$resource][$action];
            }
        }
        $admin->syncPermissions($adminPermissions);

        // Manager: semua permission (termasuk delete sensitive resources)
        $manager->syncPermissions(collect($permissions)->flatten());

        // Sales: operational resources (view, create, edit) + view sensitive resources
        $salesPermissions = [];
        foreach ($this->operationalResources as $resource) {
            foreach (['view', 'create', 'edit'] as $action) {
                $salesPermissions[] = $permissions[$resource][$action];
            }
        }
        foreach ($this->sensitiveResources as $resource) {
            $salesPermissions[] = $permissions[$resource]['view'];
        }
        $sales->syncPermissions($salesPermissions);

        // Tailor: hanya resource yang relevan dengan produksi
        $tailorPermissions = [];
        $tailorResources = ['measurements', 'orders', 'order_items', 'production_tasks', 'products', 'categories'];
        foreach ($tailorResources as $resource) {
            foreach (['view', 'create', 'edit'] as $action) {
                $tailorPermissions[] = $permissions[$resource][$action];
            }
        }
        $tailor->syncPermissions($tailorPermissions);

        // Production: resource terkait material dan produksi
        $productionPermissions = [];
        $productionResources = ['production_tasks', 'material_stocks', 'material_categories', 'suppliers', 'products', 'categories'];
        foreach ($productionResources as $resource) {
            foreach (['view', 'create', 'edit'] as $action) {
                $productionPermissions[] = $permissions[$resource][$action];
            }
        }
        $production->syncPermissions($productionPermissions);
    }
}
