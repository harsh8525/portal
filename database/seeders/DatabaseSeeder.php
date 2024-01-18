<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(SignInMethodSeeder::class);
        $this->call(HotelBedsApiSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(MailTemplateSeeder::class);
        $this->call(SmsTemplateSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(CoreAgencyTypesSeeder::class);
        $this->call(CoreBankDetailsSeeder::class);
        $this->call(CorePaymentTypesSeeder::class);
        $this->call(CoreServiceTypesSeeder::class);
        $this->call(CoreSmsTemplatesSeeder::class);
        $this->call(CoreSuppliersSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(CurrencyExchangeRatesSeeder::class);
        $this->call(LoginAttampts::class);
        $this->call(PasswordSecurity::class);
        $this->call(AmadeusApiSeeder::class);
        $this->call(AgencySeeder::class);
        $this->call(AgencyRelatedSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(AirlineSeeder::class);
        $this->call(AirportSeeder::class);
        $this->call(ApiLoginSeeder::class);
    }
}
