<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        Module::upsert([
            //common modules
            ['group_code'=>'BOOKING','group_name'=>'Bookings','module_code'=>'BOOKING','module_name'=>'Bookings','is_managerapp'=>1,'is_b2bapp'=>1,'is_supplierapp'=>1,'sort_order'=>1,'b2b_sort_order'=>2,'supplier_sort_order'=>0],
            // ['group_code'=>'PRODUCTS','group_name'=>'Products','module_code'=>'PRODUCTS','module_name'=>'Products','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>1,'sort_order'=>2,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'PROPERTY','group_name'=>'Property','module_code'=>'PROPERTY','module_name'=>'Property','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>1,'sort_order'=>3,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'NOTIFICATION','group_name'=>'Notification','module_code'=>'NOTIFICATION','module_name'=>'Notifications','is_managerapp'=>1,'is_b2bapp'=>1,'is_supplierapp'=>1,'sort_order'=>4],
            
            // ['group_code'=>'USERS','group_name'=>'Users','module_code'=>'USERS','module_name'=>'Users','is_managerapp'=>1,'is_b2bapp'=>1,'is_supplierapp'=>1,'sort_order'=>5],
            
            
            //Manager Modules
            // ['group_code'=>'PRODUCTS','group_name'=>'Products','module_code'=>'PRODUCT_TYPES','module_name'=>'Product Types','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>6,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'PRODUCTS','group_name'=>'Products','module_code'=>'PRODUCT_THEMES','module_name'=>'Product Themes','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>7,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            
            // Manager Modules-CUSTOMERS-Group
            ['group_code'=>'CUSTOMERS','group_name'=>'Customers','module_code'=>'CUSTOMERS','module_name'=>'Customers','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>8,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'CUSTOMERS','group_name'=>'Customers','module_code'=>'CUSTOMERS_LIST','module_name'=>'Customers List','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>9,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'CUSTOMERS','group_name'=>'Customers','module_code'=>'CUSTOMERS_REVIEW','module_name'=>'Customer Review','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>10,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            //Manager Modules-AGENCY
            ['group_code'=>'AGENCY','group_name'=>'Agency','module_code'=>'AGENCY','module_name'=>'Agency','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>11,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
             
            // Manager Modules-USERS-Group
            ['group_code'=>'USERS','group_name'=>'Users','module_code'=>'USERS','module_name'=>'Users','is_managerapp'=>1,'is_b2bapp'=>1,'is_supplierapp'=>1,'sort_order'=>12,'b2b_sort_order'=>7,'supplier_sort_order'=>0],
            ['group_code'=>'USERS','group_name'=>'Users','module_code'=>'USERS_LIST','module_name'=>'Users List','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>13,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'USERS','group_name'=>'Users','module_code'=>'ROLES_PERMISSION','module_name'=>'Roles & Permission','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>14,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            
            // Manager Modules- Preferences-Group
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'PREFERENCES','module_name'=>'Preferences','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>15,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'GENERAL','module_name'=>'General','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>16,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'LOGIN_ATTEMPTS','module_name'=>'Login Attempts','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>17,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'PASSWORD_SECURITY','module_name'=>'Password Security','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>18,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'SMTP_SETTINGS','module_name'=>'SMTP Setting','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>19,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'SIGN_IN_METHOD','module_name'=>'Sign In Method','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>20,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'CURRENCIES','module_name'=>'Currencies','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>21,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'MAIL_CHIMP','module_name'=>'Mail Chimp','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>22,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'NOTIFICATIONS','module_name'=>'Notifications','is_managerapp'=>1,'is_b2bapp'=>1,'is_supplierapp'=>0,'sort_order'=>23,'b2b_sort_order'=>9,'supplier_sort_order'=>0],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'SMS_SETTINGS','module_name'=>'SMS Setting','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>24,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            // ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'HOTEL_BEDS_API','module_name'=>'Hotel Beds Api','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>25],
            // ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'AMADEUS_API','module_name'=>'Amadeus API','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>26],
            ['group_code'=>'PREFERENCES','group_name'=>'Preferences','module_code'=>'LANGUAGE','module_name'=>'Language','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>27,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
           
            // Manager Modules- Operational Data-Group
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'OPERATIONAL_DATA','module_name'=>'Operational Data','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>28,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'AGENCY_TYPE','module_name'=>'Agency Type','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>29,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'SERVICE_TYPE','module_name'=>'Service Type','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>30,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'SUPPLIERS','module_name'=>'Suppliers','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>31,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'PAYMENT_METHOD','module_name'=>'Payment Method','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>32,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'PAYMENT_GATEWAY','module_name'=>'Payment Gateway','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>33,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'OPERATIONAL_DATA','group_name'=>'Operational Data','module_code'=>'BANKS','module_name'=>'Banks','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>34,'b2b_sort_order'=>0,'supplier_sort_order'=>0],

            // Manager Modules- Templates-Group
            ['group_code'=>'TEMPLATES','group_name'=>'Templates','module_code'=>'TEMPLATES','module_name'=>'Templates','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>35,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'TEMPLATES','group_name'=>'Templates','module_code'=>'MAIL_TEMPLATES','module_name'=>'Mail Templates','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>36,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'TEMPLATES','group_name'=>'Templates','module_code'=>'SMS_TEMPLATES','module_name'=>'Sms Templates','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>37,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            
            // Manager Modules- Geography-Group
            ['group_code'=>'GEOGRAPHY','group_name'=>'Geography','module_code'=>'GEOGRAPHY','module_name'=>'Geography','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>38,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'GEOGRAPHY','group_name'=>'Geography','module_code'=>'AIRPORTS','module_name'=>'Airports','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>39,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'GEOGRAPHY','group_name'=>'Geography','module_code'=>'REGIONS','module_name'=>'Regions','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>40,'b2b_sort_order'=>0,'supplier_sort_order'=>0],

            // Manager Modules- B2C-Group
            ['group_code'=>'B2C','group_name'=>'B2C','module_code'=>'B2C','module_name'=>'B2C','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>41,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'B2C','group_name'=>'B2C','module_code'=>'CMS_PAGES','module_name'=>'Cms Pages','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>42,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'B2C','group_name'=>'B2C','module_code'=>'HOME_BANNERS','module_name'=>'Home Banners','is_managerapp'=>1,'is_b2bapp'=>0,'is_supplierapp'=>0,'sort_order'=>43,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            
            
            //B2B Module
            ['group_code'=>'SEARCH','group_name'=>'Search','module_code'=>'SEARCH','module_name'=>'Search','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>1,'b2b_sort_order'=>1,'supplier_sort_order'=>0],
            ['group_code'=>'TRAVEL_CALENDAR','group_name'=>'Travel Calendar','module_code'=>'TRAVEL_CALENDAR','module_name'=>'Travel Calendar','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>45,'b2b_sort_order'=>3,'supplier_sort_order'=>0],
            ['group_code'=>'DEPOSIT','group_name'=>'Deposit','module_code'=>'DEPOSIT','module_name'=>'Deposit','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>46,'b2b_sort_order'=>4,'supplier_sort_order'=>0],
            ['group_code'=>'DEPOSIT','group_name'=>'Deposit','module_code'=>'DEPOSIT_ACCOUNT_LOG','module_name'=>'Deposit Account Log','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>47,'b2b_sort_order'=>5,'supplier_sort_order'=>0],
            ['group_code'=>'DEPOSIT','group_name'=>'Deposit','module_code'=>'DEPOSIT_REQUEST','module_name'=>'Deposit Requests','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>48,'b2b_sort_order'=>6,'supplier_sort_order'=>0],
            ['group_code'=>'MARKUP','group_name'=>'Markup','module_code'=>'MARKUP','module_name'=>'Mark Ups','is_b2bapp'=>1,'is_managerapp'=>0,'is_supplierapp'=>0,'sort_order'=>49,'b2b_sort_order'=>8,'supplier_sort_order'=>0],
            
            
            //Supplier Modules
            ['group_code'=>'AVAILABILITY','group_name'=>'Availability','module_code'=>'AVAILABILITY','module_name'=>'Availability','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>50,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'PRODUCT_AVAILABILITY','group_name'=>'Product Availability','module_code'=>'PRODUCT_AVAILABILITY','module_name'=>'Product Availability','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>51,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'REVIEWS','group_name'=>'Reviews','module_code'=>'REVIEWS','module_name'=>'Reviews','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>52,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'FINANCE','group_name'=>'Finanace','module_code'=>'FINANCE','module_name'=>'Finance','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>53,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'ACCOUNT','group_name'=>'Account','module_code'=>'ACCOUNT','module_name'=>'Account','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>54,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            ['group_code'=>'CONTACT_US','group_name'=>'Contact Us','module_code'=>'CONTACT_US','module_name'=>'Contact Us','is_supplierapp'=>1,'is_managerapp'=>0,'is_b2bapp'=>0,'sort_order'=>55,'b2b_sort_order'=>0,'supplier_sort_order'=>0],
            
        ],'module_code');        
    }
}
