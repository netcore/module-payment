<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Netcore\Translator\Helpers\TransHelper;

class PaymentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menuItems = [
            'leftAdminMenu' => [
                [
                    'name'       => 'Payments',
                    'icon'       => 'fa-credit-card',
                    'type'       => 'url',
                    'value'      => 'javascript:;',
                    'module'     => 'Payment',
                    'is_active'  => 1,
                    'parameters' => json_encode([]),
                    'active_resolver' => 'admin::payment.*,admin.payment-config.*',

                    'children' => [
                        [
                            'name'            => 'Payment list',
                            'type'            => 'route',
                            'value'           => 'admin::payment.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::payment.*',
                            'parameters'      => json_encode([])
                        ]
                    ]
                ],
            ]
        ];

        foreach ($menuItems as $key => $items) {
            $menu = Menu::firstOrCreate([
                'key' => $key
            ]);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $key))
                ];
            }
            $menu->updateTranslations($translations);

            foreach ($items as $item) {
                $row = $menu->items()->firstOrCreate(array_except($item, ['name', 'value', 'parameters', 'children']));

                $translations = [];
                foreach (TransHelper::getAllLanguages() as $language) {
                    $translations[$language->iso_code] = [
                        'name'       => $item['name'],
                        'value'      => $item['value'],
                        'parameters' => $item['parameters']
                    ];
                }
                $row->updateTranslations($translations);

                foreach ($item['children'] as $child) {
                    $child['menu_id'] = $menu->id;

                    $c = $row->children()->firstOrCreate(array_except($child, ['name', 'value', 'parameters']));
                    $translations = [];
                    foreach (TransHelper::getAllLanguages() as $language) {
                        $translations[$language->iso_code] = [
                            'name'       => $child['name'],
                            'value'      => $child['value'],
                            'parameters' => $child['parameters']
                        ];
                    }
                    $c->updateTranslations($translations);
                }
            }
        }
    }
}
