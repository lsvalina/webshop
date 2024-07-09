<?php

namespace Database\Seeders;

use App\Models\PriceList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    const REFRESH_TABLES = ['users', 'categories', 'products', 'category_product', 'contract_lists', 'countries',
        'country_category', 'price_lists', 'price_list_product', 'pricing_modifiers', 'order_pricing_modifier',
        'order_product', 'orders'];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->__truncate(self::REFRESH_TABLES);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PriceListSeeder::class);
        $this->call(ContractListSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PricingModifierSeeder::class);
    }

    /**
     * Truncate list of tables
     *
     * @param array $truncateTablesList
     * @return void
     */
    private function __truncate(array $truncateTablesList) : void
    {
        Schema::disableForeignKeyConstraints();
        foreach($truncateTablesList as $table){
            DB::table($table)->truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
