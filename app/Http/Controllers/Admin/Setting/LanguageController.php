<?php

/**
 * @package     Preferences
 * @subpackage  Language
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Language.
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use App\Traits\ActiveLog; 
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

class LanguageController extends Controller
{

    /**
     * Display a listing of the language.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {

        if (!hasPermission('LANGUAGE', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Language";
        $header['heading'] = "Language";

        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }


        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'language_code' => (request()->input('language_code') != NULL) ? request()->input('language_code') : '',
            'language_name' => (request()->input('language_name') != NULL) ? request()->input('language_name') : '',
            'language_type' => (request()->input('language_type') != NULL) ? request()->input('language_type') : '',
            'is_default' => (request()->input('is_default') != NULL) ? request()->input('is_default') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );

        if (request()->input('language_code') != NULL) {
            $filter['where'][] = ['core_languages.language_code', 'like', '%' . request()->input('language_code') . '%'];
        }
        if (request()->input('language_name') != NULL) {
            $filter['where'][] = ['core_languages.language_name', 'like', '%' . request()->input('language_name') . '%'];
        }
        if (request()->input('language_type') != NULL) {
            $filter['where'][] = ['core_languages.language_type', 'like', '%' . request()->input('language_type') . '%'];
        }

        if (request()->input('is_default') != NULL) {
            $filter['where'][] = ['core_languages.is_default', '=', request()->input('is_default')];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['core_languages.status', '=', request()->input('status')];
        }

        $languageListData = Language::getLanguage($filter);
        $languageListCount = Language::count();
        $languageData = $languageListData['data'];
        $defaultLanguageCode = count(Setting::where('config_key', '=', 'general|site|defaultLanguageCode')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|defaultLanguageCode')->get('value')[0]['value'] : "";

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $languageListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($languageListData['status'] == 1) {
            return view('admin/setting/language/index')->with(['header' => $header, 'languageData' => $languageData, 'languageListCount' => $languageListCount, 'defaultLanguageCode' => $defaultLanguageCode, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/setting/language/index')->with(['error' => $languageListData['message'], 'header' => $header, 'languageData' => $languageData, 'languageListCount' => $languageListCount, 'defaultLanguageCode' => $defaultLanguageCode, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new language.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function create()
    {
        if (!hasPermission('LANGUAGE', 'create')) {
            return view('admin/401');
        }
        $header['title'] = "Language - Add";
        $header['heading'] = "Language - Add";

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/setting/language/add')->with(['header' => $header]);
    }

    /**
     * Store a newly created language in storage - Insert Form Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {

        if (!hasPermission('LANGUAGE', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->all();
        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $response = Language::createLanguage($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('language.index')->with('success', $response['message']);
        } else {
            return redirect()->route('language.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified language.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function show($id)
    {
        if (!hasPermission('LANGUAGE', 'read')) {
            return view('admin/401');
        }
        $header['title'] = 'Language - View';
        $header['heading'] = 'Language - View';
        $filter = array(
            'id' => $id
        );
        $response = Language::getLanguage($filter);
        $languageDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/setting/language/view')->with(['header' => $header, 'languageDetail' => $languageDetail]);
        } else {
            return redirect()->route('language.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified language.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function edit($id)
    {
        if (!hasPermission('LANGUAGE', 'update')) {
            return view('admin/401');
        }

        $header['title'] = 'Language - Edit';
        $header['heading'] = 'Language - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = Language::getLanguage($filter);

        $languageDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/setting/language/update')->with(['header' => $header, 'languageDetail' => $languageDetail]);
        } else {
            return redirect()->route('language.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified language in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('LANGUAGE', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['language_id', 'language_code', 'language_name', 'language_type', 'sort_order', 'status', 'is_default']);
        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = Language::updateLanguage($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified language from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteLanguage(Request $request)
    {
        if (!hasPermission('LANGUAGE', 'delete')) {
            return view('admin/401');
        }

        $url = URL::previous();
        $languageIDs = explode(',', $request->input('language_id'));

        $message = "";
        foreach ($languageIDs as $language_id) {
            $response = Language::deleteLanguage($language_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check language code exist from language database.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExistCode(Request $request)
    {
        $matchListData = [];
        if (request()->input('language_code') && request()->input('language_code') != "") {
            if (request()->input('language_id')) {
                $matchListData = Language::where('language_code', request()->input('language_code'))->where('id', '!=', request()->input('language_id'))->get()->toArray();
            } else {
                $matchListData = Language::where('language_code', request()->input('language_code'))->where('status', '!=', '2')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check Existkey
     * 
     */
    public function checkExistKey(Request $request)
    {
        $matchListData = [];
        if (request()->input('key') && request()->input('key') != "") {

            $langCode = Language::where('id', $request->id)->value('language_code');

            $fileName = "B2CTranslate.php";
            $path = resource_path('lang/' . $langCode . '');
            $languageFiles = resource_path('lang/' . $langCode . '/B2CTranslate.php');
            $content = include($languageFiles);
            $matchListData = array_key_exists($request->key, $content);
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * translate Function
     * 
     */
    public function translate($id)
    {
        if (!hasPermission('LANGUAGE', 'read')) {
            return view('admin/401');
        }
        $search_key = "";
        $search_value = "";
        $search_key_arabic = "";
        $search_value_arabic = "";
        if (request()->search_key) {
            $search_key = request()->search_key;
        }
        if (request()->search_value) {
            $search_value = request()->search_value;
        }
        if (request()->search_value_arabic) {
            $search_value_arabic = request()->search_value_arabic;
        }

        $header['title'] = "Language - B2C Translate ";
        $header['heading'] = "Language - B2C Translate Add";
        $langCode = Language::where('id', $id)->value('language_code');

        $folder_path = storage_path('app/public/locale/b2c/');
        $path = $folder_path . $langCode . '.json';

        // Check if the folder exists, if not, create it
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        // Check if the file exists, if not, create it
        if (!file_exists($path)) {
            file_put_contents($path, '{}');

            chmod($path, 0777);
        }
        $EnglishJsonContent = Storage::disk('public')->get('/locale/b2c/en.json');
        $arabicJsonContent = Storage::disk('public')->get('/locale/b2c/ar.json');

        // Decode the JSON content
        $englishTranslationsdata = json_decode($EnglishJsonContent, true);
        $arabicTranslationsdata = json_decode($arabicJsonContent, true);
        // echo "<pre>";print_r($englishTranslationsdata);die;
        if ($search_key) {
            $translations = collect($englishTranslationsdata)->filter(function ($value, $key) use ($search_key) {
                return preg_match("/$search_key/i", $key);
            })->all();
        } else if ($search_value) {

            $translations = collect($englishTranslationsdata)->filter(function ($value, $key) use ($search_value) {
                return preg_match("/$search_value/i", $value);
            })->all();
        } else {
            $translations = $englishTranslationsdata;
        }
        if ($search_key_arabic) {
            $arabicTranslations = collect($englishTranslationsdata)->filter(function ($value, $key) use ($search_key_arabic) {
                return preg_match("/$search_key_arabic/i", $key);
            })->all();
        } else if ($search_value_arabic) {
            $arabicTranslations = collect($arabicTranslationsdata)->filter(function ($value, $key) use ($search_value_arabic) {
                return preg_match("/$search_value_arabic/i", $value);
            })->all();
            
        } else {
            $arabicTranslations = $arabicTranslationsdata;
        }
        if($search_value_arabic){

            $translations = array_intersect_key($translations,$arabicTranslations);
        }
        $newArray = array(
            'english' => $translations,
            'arabic' => $arabicTranslations
        );
        sleep(3);
        return view('admin/setting/language/translate')->with(['header' => $header, 'content' => $newArray, 'id' => $id, 'langCode' => $langCode]);
    }

    /**
     * createLangTranslator Function
     * 
     */
    public function createLangTranslator($id)
    {
        if (!hasPermission('LANGUAGE', 'create')) {
            return view('admin/401');
        }

        $header['title'] = "Language - Translate Add";
        $header['heading'] = "Language - Translate Add";
        return view('admin/setting/language/add-translate')->with(['header' => $header, 'id' => $id]);
    }

    /**
     * Language - Translate Add
     * 
     */
    public function storeLangTranslator(Request $request)
    {
        if (!hasPermission('LANGUAGE', 'create')) {
            return view('admin/401');
        }

        $id = $request->id;
        $header['title'] = "Language - Translate ";
        $header['heading'] = "Language - Translate Add";

        $langCode = Language::where('id', $id)->value('language_code');
        $fileName = "B2CTranslate.php";
        $path = resource_path('lang/' . $langCode . '');
        $languageFiles = resource_path('lang/' . $langCode . '/B2CTranslate.php');
        $translations = include($languageFiles);
        $newArray = array(
            $request['key'] => $request['value']
        );

        $data =  array_merge($translations, $newArray);
        $content = "<?php" . "\n";
        $content .= "return ";
        $content .= var_export($data, true);
        $content .= "\n" . "?>";
        $result = file_put_contents($path . "/" . $fileName, $content);

        if (!empty($result)) {
            sleep(5);
            return redirect()->route('language.translate.b2c', $id);
        }
    }

    /**
     * Language - Translate Update
     * 
     */
    public function updateLangTranslator(Request $request)
    {
        if (!hasPermission('LANGUAGE', 'update')) {
            return view('admin/401');
        }
        $id = $request->id;
        $header['title'] = "Language - B2C Translate";
        $header['heading'] = "Language - B2C Translate Add";

        //insert english language json
        $englishFileName = "en.json";
        $englishPath = storage_path('app/public/locale/b2c/');
        $englishJsonContent = Storage::disk('public')->get('/locale/b2c/en.json');
        $englishTranslationsdata = json_decode($englishJsonContent, true);
        if (!is_dir($englishPath)) {
            mkdir($englishPath, 0777);

            chmod($englishPath, 0777);
        }
        $data = [];
        $englishCombinedArray = array_combine(array_values($request->key), array_values($request->value_english));
        $englishMergedArray = array_merge($englishTranslationsdata, $englishCombinedArray);
        $englishContent = json_encode($englishMergedArray, JSON_UNESCAPED_UNICODE);
        $englishResult = file_put_contents($englishPath . "/" . $englishFileName, $englishContent);


        $arabicFileName = "ar.json";
        $path = storage_path('app/public/locale/b2c/');
        $arabicJsonContent = Storage::disk('public')->get('/locale/b2c/ar.json');
        $arabicTranslationsdata = json_decode($arabicJsonContent, true);
        if (!is_dir($path)) {
            mkdir($path, 0777);

            chmod($path, 0777);
        }
        $data = [];
        $arabicCombinedArray = array_combine(array_values($request->key), array_values($request->value_arabic));
        $arabicMergedArray = array_merge($arabicTranslationsdata, $arabicCombinedArray);
        $arabicContent = json_encode($arabicMergedArray, JSON_UNESCAPED_UNICODE);
        $arabicResult = file_put_contents($path . "/" . $arabicFileName, $arabicContent);

        if (!empty($arabicResult) && !empty($englishResult)) {
            sleep(5);
            return redirect()->back();
        }
    }

    /**
     * translateB2B Function
     * 
     */
    public function translateB2B($id)
    {
        if (!hasPermission('LANGUAGE', 'read')) {
            return view('admin/401');
        }
        $search_key = "";
        $search_value = "";
        if (request()->search_key) {
            $search_key = request()->search_key;
        }
        if (request()->search_value) {
            $search_value = request()->search_value;
        }

        $header['title'] = "Language - B2B Translate ";
        $header['heading'] = "Language - B2B Translate Add";
        $langCode = Language::where('id', $id)->value('language_code');

        $folder_path = storage_path('app/public/locale/b2b/');
        $path = $folder_path . $langCode . '.json';

        // Check if the folder exists, if not, create it
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);

            chmod($folder_path, 0777);
        }

        // Check if the file exists, if not, create it
        if (!file_exists($path)) {
            file_put_contents($path, '{}');

            chmod($path, 0777);
        }

        $jsonContent = Storage::disk('public')->get('/locale/b2b/' . $langCode . '.json');

        // Decode the JSON content
        $translationsdata = json_decode($jsonContent, true);
        if ($search_key) {
            $translations = collect($translationsdata)->filter(function ($value, $key) use ($search_key) {
                return str_contains($key, $search_key);
            })->all();
        } else if ($search_value) {
            $translations = collect($translationsdata)->filter(function ($value, $key) use ($search_value) {
                return str_contains($value, $search_value);
            })->all();
        } else {
            $translations = $translationsdata;
        }
        sleep(3);
        return view('admin/setting/language/translate-b2b')->with(['header' => $header, 'content' => $translations, 'id' => $id, 'langCode' => $langCode]);
    }

    /**
     * updateLangTranslatorB2B Function
     * 
     */
    /* Start updateLangTranslatorB2B Function */
    public function updateLangTranslatorB2B(Request $request)
    {
        if (!hasPermission('LANGUAGE', 'update')) {
            return view('admin/401');
        }
        $id = $request->id;
        $header['title'] = "Language - B2B Translate";
        $header['heading'] = "Language - B2B Translate Add";

        $langCode = Language::where('id', $id)->value('language_code');
        $fileName = $langCode . ".json";
        $path = storage_path('app/public/locale/b2b/');
        $jsonContent = Storage::disk('public')->get('/locale/b2c/' . $langCode . '.json');
        $translationsdata = json_decode($jsonContent, true);
        if (!is_dir($path)) {
            mkdir($path, 0777);

            chmod($path, 0777);
        }
        $data = [];
        $combinedArray = array_combine(array_values($request->key), array_values($request->value));
        $mergedArray = array_merge($translationsdata, $combinedArray);
        $content = json_encode($mergedArray, JSON_UNESCAPED_UNICODE);

        $result = file_put_contents($path . "/" . $fileName, $content);

        if (!empty($result)) {
            sleep(5);
            return redirect()->back();
        }
    }
    /* End updateLangTranslatorB2B Function */

    /**
     * translateJson Function
     * 
     */
    /* Start translate Function */
    public function translateJson($id)
    {
        if (!hasPermission('LANGUAGE', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Language - Translate ";
        $header['heading'] = "Language - Translate Add";
        $langCode = Language::where('id', $id)->value('language_code');

        sleep(3);
        return view('admin/setting/language/translate_json')->with(['header' => $header, 'id' => $id]);
    }

    /* End Lget File Contents Function*/

    /**
     * getFileContents Function
     * 
     */
    public function getFileContents($id)
    {
        $langCode = Language::where('id', $id)->value('language_code');
        $fileContents = file_get_contents(storage_path('app/public/locale/' . $langCode . '.json'));

        return response()->json(['contents' => $fileContents]);
    }

    /* End Lang translate Json Function*/
    /**
     * storeLangTranslatorJson Function
     * 
     */
    public function storeLangTranslatorJson(Request $request)
    {
        if (!hasPermission('LANGUAGE', 'create')) {
            return view('admin/401');
        }

        $id = $request->id;
        $header['title'] = "Language - Translate ";
        $header['heading'] = "Language - Translate Add";

        $langCode = Language::where('id', $id)->value('language_code');

        $fileName = $langCode . ".json";
        $path = storage_path('app/public/locale/');
        if (!is_dir($path)) {
            /* Directory does not exist, so lets create it. */
            mkdir($path, 0777);
        }
        $data = json_decode($request->lang_file_json, true);
        $makeJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $result = file_put_contents($path . "/" . $fileName, $makeJson);

        if (!empty($result)) {
            sleep(5);
            return redirect()->route('language.translate-json', $id);
        }
    }
}
