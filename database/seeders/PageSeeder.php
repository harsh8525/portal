<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pageData = array(
            array(
                'page_code' => 'about',
                'status' => '1',
                'slug_url' => 'about-us',
                'pages_data' => array(
                    array(
                        'page_title' => 'About Us',
                        'page_content' => 'Page Content',
                        'meta_title' => 'Add Meta title',
                        'meta_description' => 'Add Meta description',
                        'keywords' => 'Add Keywords',
                        'language_code' => 'en',
                    ),
                    array(
                        'page_title' => 'معلومات عنا',
                        'page_content' => 'محتوى الصفحة',
                        'meta_title' => 'أضف عنوان ميتا',
                        'meta_description' => 'إضافة وصف ميتا',
                        'keywords' => 'أضف الكلمات الرئيسية',
                        'language_code' => 'ar',
                    )
                )
            )

        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\PageI18ns::truncate();
        Page::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($pageData as $key => $page) {
            Page::createSeederPage($page);
        }
    }
    // $pages1 = ['page_code' => 'about', 'status' => 1, 'slug_url' => 'about-us'];
    // $pages1 = ['page_code' => 'about', 'page_title' => 'About Us', 'slug_url' => 'about-us', 'page_content' => '', 'meta_title' => 'Add Meta title', 'meta_description' => 'Add Meta description', 'keywords' => 'Add Keywords'];
    // $pages2 = ['page_code' => 'terms_and_conditions', 'page_title' => 'Terms & Conditions', 'slug_url' => 'terms-and-condition', 'page_content' => '', 'meta_title' => 'Add Meta title', 'meta_description' => 'Add Meta description', 'keywords' => 'Add Keywords'];
    // $pages3 = ['page_code' => 'privacy_policy', 'page_title' => 'Privacy Policy', 'slug_url' => 'privacy-policy', 'page_content' => '', 'meta_title' => 'Add Meta title', 'meta_description' => 'Add Meta description', 'keywords' => 'Add Keywords'];
    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    // Page::truncate();
    // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    // Page::updateOrCreate(['page_code' => 'about'], $pages1);
    // PageI18ns::updateOrCreate(['page_code' => 'terms_and_conditions'], $pages2);
    // PageI18ns::updateOrCreate(['page_code' => 'privacy_policy'], $pages3);
    // Page::insert($pages);
    // }
}
