<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Permissions\PermissionCategory;
use App\Models\Permissions\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Sample Item Management',
                'description' => 'Manage Sample Items',
                'icon' => 'fa fa-cubes',
                'items' => [
                    [
                        'name' => 'admin.sample-items.crud',
                        'description' => 'Manage Sample Items',
                    ],
                ],
            ],
            [
                'name' => 'Content Management',
                'description' => 'Manage Pages & Contents',
                'icon' => 'fa fa-feather',
                'items' => [
                    [
                        'name' => 'admin.pages.crud',
                        'description' => 'Manage Pages',
                    ],
                    [
                        'name' => 'admin.page-items.crud',
                        'description' => 'Manage Page Contents',
                    ],
                ],
            ],
            [
                'name' => 'Admin Management',
                'description' => 'Manage Administrators',
                'icon' => 'fa fa-user-shield',
                'items' => [
                    [
                        'name' => 'admin.admin-users.crud',
                        'description' => 'Manage Administrator Accounts',
                    ],
                    [
                        'name' => 'admin.roles.crud',
                        'description' => 'Manage Admin Roles & Permissions',
                    ],
                ],
            ],
            [
                'name' => 'User Management',
                'description' => 'Manage User Accounts',
                'icon' => 'fa fa-users',
                'items' => [
                    [
                        'name' => 'admin.users.crud',
                        'description' => 'Manage User Accounts',
                    ],
                ],
            ],
            [
                'name' => 'Activity Logs',
                'description' => 'View Activity Logs',
                'icon' => 'fa fa-shield-alt',
                'items' => [
                    [
                        'name' => 'admin.activity-logs.crud',
                        'description' => 'View Activity Logs',
                    ],
                ],
            ],


            [
                'name' => 'Order status types',
                'description' => 'Managing order status types',
                'icon' => 'fas fa-list',
                'items' => [
                    [
                        'name' => 'admin.status-types.crud',
                        'description' => 'Manage order status types',
                    ],
                ],
            ],

            [
                'name' => 'Orders',
                'description' => 'Managing orders',
                'icon' => 'fas fa-clipboard-list',
                'items' => [
                    [
                        'name' => 'admin.invoices.crud',
                        'description' => 'Manage orders',
                    ],

                ],
            ],

            [
                'name' => 'Specializations Management',
                'description' => 'Managing specializations',
                'icon' => 'fas fa-certificate',
                'items' => [
                    [
                        'name' => 'admin.specializations.crud',
                        'description' => 'Manage Specializations',
                    ],
                ],
            ],

            [
                'name' => 'Products Management',
                'description' => 'Managing Products',
                'icon' => 'fa fa-cubes',
                'items' => [
                    [
                        'name' => 'admin.products.crud',
                        'description' => 'Manage products',
                    ],

                ],
            ],

            [
                'name' => 'Inventories Management',
                'description' => 'Managing Inventories',
                'icon' => 'fa fa-cubes',
                'items' => [
                    [
                        'name' => 'admin.inventories.crud',
                        'description' => 'Update inventories',
                    ],
                ],
            ],
            [
                'name' => 'Voucher Management',
                'description' => 'Managing Vouchers',
                'icon' => 'fas fa-ticket-alt',
                'items' => [
                    [
                        'name' => 'admin.vouchers.crud',
                        'description' => 'Manage Vouchers',
                    ],
                ],
            ],
            [
                'name' => 'Pharmacy Management',
                'description' => 'Managing Pharmacy',
                'icon' => 'fa fa-cubes',
                'items' => [
                    [
                        'name' => 'admin.pharmacies.crud',
                        'description' => 'Manage pharmacies',
                    ],

                ],
            ],

            [
                'name' => 'Doctors Management',
                'description' => 'Managing Doctors',
                'icon' => 'fas fa-stethoscope',
                'items' => [
                    [
                        'name' => 'admin.doctors.crud',
                        'description' => 'Manage doctors',
                    ],
                ],
            ],            


            [
                'name' => 'Medical Representative Management',
                'description' => 'Managing Medical Representative',
                'icon' => 'fas fa-user',
                'items' => [
                    [
                        'name' => 'admin.medreps.crud',
                        'description' => 'Manage medical representative',
                    ],
                ],
            ],

            [
                'name' => 'Calls Management',
                'description' => 'Managing Calls',
                'icon' => 'fas fa-phone',
                'items' => [
                    [
                        'name' => 'admin.calls.crud',
                        'description' => 'Manage calls',
                    ],
                ],
            ],


            [
                'name' => 'Target Calls Management',
                'description' => 'Managing target calls',
                'icon' => 'fas fa-phone',
                'items' => [
                    [
                        'name' => 'admin.target-calls.crud',
                        'description' => 'Manage target calls',
                    ],
                ],
            ],

            [
                'name' => 'Shipping Fee (standard)  Management',
                'description' => 'Managing shipping fee (standard)',
                'icon' => 'fas fa-money-bill-wave-alt',
                'items' => [
                    [
                        'name' => 'admin.shipping-standards.crud',
                        'description' => 'Manage shipping fee (standard)',
                    ],
                ],
            ],


            [
                'name' => 'Shipping Fee (express)  Management',
                'description' => 'Managing shipping fee (express)',
                'icon' => 'fas fa-money-bill-wave-alt',
                'items' => [
                    [
                        'name' => 'admin.shipping-expresses.crud',
                        'description' => 'Manage shipping fee (express)',
                    ],
                ],
            ],


            [
                'name' => 'Bank details Management',
                'description' => 'Managing bank details',
                'icon' => 'fas fa-money-check',
                'items' => [
                    [
                        'name' => 'admin.bank-details.crud',
                        'description' => 'Manage bank details',
                    ],
                ],
            ],

            [
                'name' => 'Articles Management',
                'description' => 'Managing articles',
                'icon' => 'fa fa-newspaper',
                'items' => [
                    [
                        'name' => 'admin.articles.crud',
                        'description' => 'Manage articles',
                    ],
                ],
            ],

            [
                'name' => 'Redeems Management',
                'description' => 'Managing Redeems',
                'icon' => 'fas fa-heart',
                'items' => [
                    [
                        'name' => 'admin.redeems.crud',
                        'description' => 'Manage redeems',
                    ],
                ],
            ],

            [
                'name' => 'Points Management',
                'description' => 'Managing points',
                'icon' => 'fas fa-coins',
                'items' => [
                    [
                        'name' => 'admin.points.index',
                        'description' => 'Manage points',
                    ],
                ],
            ],

        ];

    	foreach ($categories as $category) {
            $permissions = $category['items'];
            unset($category['items']);

            $item = PermissionCategory::where('name', $category['name'])->first();

            if (!$item) {
                $this->command->info('Adding permission category ' . $category['name'] . '...');
                $item = PermissionCategory::create($category);
            } else {
                $this->command->warn('Updating permission category ' . $category['name'] . '...');
                $item->update($category);
            }


            foreach ($permissions as $permission) {
                $permissionItem = Permission::where('name', $permission['name'])->first();
                
                if (!$permissionItem) {
                    $this->command->info('Adding permission ' . $permission['name'] . '...');
                    $item->permissions()->create($permission);
                } else {
                    $this->command->warn('Updating permission ' . $permission['name'] . '...');
                    unset($permission['name']);
                    $permissionItem->update($permission);
                }
            }
    	}
    }
}
