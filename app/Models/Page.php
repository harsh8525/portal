<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PageI18ns;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;

class Page extends Model

{
    use HasFactory, Uuids, SoftDeletes;

    public function pageCodeName()
    {
        return $this->hasMany('App\Models\PageI18ns', 'page_id', 'id');
    }
    protected $guarded = [];
    protected static $logAttributes = ['page_code', 'slug_url', 'status'];
    protected static $logName = 'pages';


    /**
     * get list or single or all records to display
     */
    public static function getPages($option = array())
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = Page::query();
                $query->with(['pageCodeName']);
                $query->select(
                    'pages.*',
                    DB::raw('(CASE WHEN pages.status = "0" THEN "In-Active" '
                        . 'WHEN pages.status = "1" THEN "Active" '
                        . 'END) AS pages_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Page::query();
                $query->with(['pageCodeName']);


                $query->select(
                    "pages.*",
                    DB::raw('(CASE WHEN pages.status = "inactive" THEN "In-Active" '
                        . 'WHEN pages.status = "active" THEN "Active" '
                        . 'END) AS page_status_text')
                );

                if ($config['order_by'] == 'page_title') {
                    $query->join('page_i18ns', 'pages.id', '=', 'page_i18ns.page_id')
                        ->where('page_i18ns.language_code', 'en')
                        ->orderBy('page_i18ns.page_title', $config['sorting']);
                }
                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'meta_title' || $config['order_by'] == 'keywords') {

                    $query->orderBy($config['order_by'], $config['sorting']);
                }
                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->whereHas('pageCodeName', function ($q) use ($where) {
                            $q->where($where[0], $where[1], $where[2]);
                        });
                    }
                }
                if (!empty($config['where'])) {
                    foreach ($config['where'] as $orWhere) {
                        $query->whereHas('pageCodeName', function ($q) use ($orWhere) {
                            $q->where($orWhere[0], $orWhere[1], $orWhere[2]);
                        });
                    }
                }
                $result = $query->paginate($config['per_page']);

                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Page list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert record in database
     */
    public static function createPage($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $pageCode = preg_replace('/[^A-Za-z0-9\-]/', ' ', $requestData['page_titles'][0]['page_title']);
        $pageData = array(
            'page_code' => str_replace(' ', '_', $pageCode),
            'slug_url' => $requestData['slug_url'],
            'status' => $requestData['status'],

        );
        try {
            DB::beginTransaction();
            $pageData = Page::create($pageData);
            if ($pageData) {

                $airlineNames['page_titles'] = $requestData['page_titles'];
                foreach ($airlineNames['page_titles'] as $key => $name) {
                    $nameData = array(
                        'page_id' => $pageData->id,
                        'page_title' => $name['page_title'],
                        'page_content' => $name['page_content'],
                        'meta_title' => $name['meta_title'],
                        'meta_description' => $name['meta_description'],
                        'keywords' => $name['keywords'],
                        'language_code' => $name['language_code'],
                    );
                    PageI18ns::create($nameData);

                    $pagemsg[] = $name['page_title'];
                }


                $return['status'] = 1;
                $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] saved successfully';
                $return['data'] = $nameData;
            }
            DB::commit();
            if ($pageData) {
                $return['status'] = 1;
                $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] Add Successfully';
                $return['data'] = $pageData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save  record : ' . $e->getMessage();
        }

        return $return;
    }

    /**
     * update record in database
     */
    protected static function updatePages($data)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            $pageCode = preg_replace('/[^A-Za-z0-9\-]/', ' ', $data['page_titles'][0]['page_title']);
            $pageData = array(
                'page_code' => str_replace(' ', '_', $pageCode),
                'slug_url' => $data['slug_url'],
                'status' => $data['status'],

            );
            try {
                DB::beginTransaction();
                $matchpageData = ['id' => $data['page_id']];
                $updatepageData = Page::updateOrCreate($matchpageData, $pageData);
                if ($updatepageData) {

                    $pageNames['page_titles'] = $data['page_titles'];
                    foreach ($pageNames['page_titles'] as $key => $name) {
                        $nameData = array(
                            'page_id' => $updatepageData->id,
                            'page_title' => $name['page_title'],
                            'page_content' => $name['page_content'],
                            'meta_title' => $name['meta_title'],
                            'meta_description' => $name['meta_description'],
                            'keywords' => $name['keywords'],
                            'language_code' => $name['language_code'],
                        );
                        $matchpageData = ['id' => $name['page_i18ns_id']];
                        PageI18ns::updateOrCreate($matchpageData, $nameData);

                        $pagemsg[] = $name['page_title'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] updated successfully';
                    $return['data'] = $nameData;
                }
                DB::commit();
                if ($pageData) {
                    $return['status'] = 1;
                    $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] updated Successfully';
                    $return['data'] = $pageData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during update page record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * delete record from database
     */
    public static function deletePqage($delete_page_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );
        $pageData = Page::where('id', $delete_page_id)->delete();
        if ($pageData) {
            $getData = PageI18ns::where('page_id', $delete_page_id)->get()->toArray();

            if ($getData->count() > 0) {
                foreach ($getData as $data) {
                    PageI18ns::where('id', $data->id)->delete();
                    $pagemsg[] = $data['page_name'];
                }
            }

            Page::where('id', $delete_page_id)->delete();

            $return['status'] = 1;
            $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] deleted successfully';
   
            return $return;
        }
    }

    /**
     * delete record from database
     */
    public static function deletePage($delete_page_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $pageData = Page::where('id', $delete_page_id)->with('pageCodeName')->withTrashed()->first()->toArray();
        $is_dependent = Page::checkDependancy($pageData['id'], $delete_page_id);
        foreach ($pageData['page_code_name'] as $key => $name) {
            $nameData = array(
                'page_title' => $name['page_title'],
                'language_code' => $name['language_code']
            );
            $pagemsg[] = $name['page_title'];
        }
        if ($is_dependent) {
            //update status to deleted
            Page::where('id', $delete_page_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'page Code [' . implode(', ', $pagemsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            Page::where('id', $delete_page_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_page_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        return $dep_modules;
    }

    /**
     * restore deleted record
     */
    public static function restorepages($restore_page_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $pageData = Page::withTrashed()->find($restore_page_id);
        if ($pageData) {
            $pageData->restore();
            $return['status'] = 1;
            $return['message'] = 'Page [' . $pageData['iso_code'] . '] restored successfully';
        }
        return $return;
    }
    /**
     * insert pages record using seeder
     */
    public static function createSeederPage($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $pageData = array(
                'page_code'  => $requestData['page_code'],
                'status'  => $requestData['status'],
                'slug_url'  => $requestData['slug_url']

            );
            // save to table
            try {
                DB::beginTransaction();
                $pageDetails = Page::create($pageData);


                if ($pageDetails) {

                    $pagesData = $requestData['pages_data'];

                    foreach ($pagesData as $key => $page) {

                        $nameData = array(
                            'page_id' => $pageDetails->id,
                            'page_title' => $page['page_title'],
                            'page_content' => $page['page_content'],
                            'meta_title' => $page['meta_title'],
                            'meta_description' => $page['meta_description'],
                            'keywords' => $page['keywords'],
                            'language_code' => $page['language_code']
                        );
                        PageI18ns::create($nameData);
                        $pagemsg[] = $page['page_title'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Page [' . implode(', ', $pagemsg) . '] saved successfully';
                    $return['data'] = $page;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }
}
