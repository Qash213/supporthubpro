<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Database\Seeders\RoleSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\TitleSeeder;
use Database\Seeders\SettingTableSeeder;
use Database\Seeders\SeopageSeeder;
use Database\Seeders\FootertextSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\EmailTemplatesSeeder;
use Database\Seeders\Customcssjs;
use Database\Seeders\CustomerrorSeeder;
use Database\Seeders\ArticleCategorySeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\FaqSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\TranslationSeeder;
use Database\Seeders\Permissiongroupupdate;
use Database\Seeders\TimezoneSeeder;
use Database\Seeders\SettingUpdateSeeder;
use Database\Seeders\EmailTemplateSeederTableSeeder;
use Database\Seeders\PermissionTableSeeder;
use Database\Seeders\Setting1TableSeeder;
use Database\Seeders\SettingtableTableSeeder;
use Database\Seeders\NewUpdateSeederV3_1;
use Database\Seeders\UpdateVersion3_2;
use Database\Seeders\UpdateVersion3_3;
use Database\Seeders\Updateversion3_4;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([

            RoleSeeder::class,
            CountrySeeder::class,
            AdminSeeder::class,
            TitleSeeder::class,
            SettingTableSeeder::class,
            SeopageSeeder::class,
            FootertextSeeder::class,
            // PagesSeeder::class,
            EmailTemplatesSeeder::class,
            Customcssjs::class,
            CustomerrorSeeder::class,
            ArticleCategorySeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            SettingUpdateSeeder::class,
            TimezoneSeeder::class,
            PermissionTableSeeder::class,
            SettingtableTableSeeder::class,
            Setting1TableSeeder::class,
            EmailTemplateSeederTableSeeder::class,
            Permissiongroupupdate::class,
            LanguageSeeder::class,
            TranslationSeeder::class,
            NewUpdateSeederV3_1::class,
            UpdateVersion3_2::class,
            UpdateVersion3_3::class,
            Updateversion3_4::class,
        ]);
    }
}
