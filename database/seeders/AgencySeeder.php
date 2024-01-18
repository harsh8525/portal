<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $b2bAgency = [
            "core_agency_type_id" => 2,
            "parent_id" => 0,
            "agency_id" => "TP-29878",
            "core_supplier_id" => "",
            "full_name" => "Travel Portal B2B",
            "short_name" => "TP",
            "contact_person_name" => "Thomas Anderson",
            "designation" => "B2B Agent",
            "license_number" => "6523254520",
            "phone_no" => "+91 9887654567",
            "fax_no" => "",
            "email" => "somya@amarinfotech.com",
            "logo" => "",
            "web_link" => "",
            "status" => "active"
            
        ];
        
        Agency::upsert($b2bAgency,'agency_id');
        
        $supplierAgency = [
            "core_agency_type_id" => 1,
            "parent_id" => 0,
            "agency_id" => "TP-50200",
            "core_supplier_id" => "",
            "full_name" => "Travel Portal Supplier",
            "short_name" => "TP",
            "contact_person_name" => "Jack Smith",
            "designation" => "Supplier Agent",
            "license_number" => "8876567890",
            "phone_no" => "+91 8896543321",
            "fax_no" => "",
            "email" => "supplier@travelportal.com",
            "logo" => "",
            "web_link" => "",
            "status" => "active"
            
        ];
        
        Agency::upsert($supplierAgency,'agency_id');
    }
}
