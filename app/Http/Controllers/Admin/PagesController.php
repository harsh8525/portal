<?php
/**
 * @package     B2C
 * @subpackage   Pages
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Pages.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\PageI18ns;
use Illuminate\Support\Facades\URL;
use App\Models\Page;
use App\Traits\ActiveLog; 

class PagesController extends Controller
{
    /**
     * Display a listing of the pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //check for permission
        if(!hasPermission('HOME_BANNERS','read')){
            return view('admin/401');
        }

        $header['title'] = @trans('Page');
        $header['heading'] = @trans('page.moduleHeading');
        $queryStringConcat = '?';
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'] ;
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'] ;
        }
        

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'page_title' => (request()->input('page_title') != NULL) ? request()->input('page_title') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'updated_at' => (request()->input('updated_at') != NULL) ? request()->input('updated_at') : '',
        );
        if(request()->input('page_title') != NULL){
            $filter['where'][] = [ 'page_title','like','%'.request()->input('page_title').'%'];
        }
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'pages.status','=',request()->input('status')];
        }
        if(request()->input('updated_at') != NULL) {
            $filter['where'][] = [ 'pages.updated_at','=',request()->input('updated_at')];
        }
        $pageListData = Page::getPages($filter);    
        $pageDataCount = Page::count();    
        $pageData = $pageListData['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $pageListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if($pageListData['status'] == 1){
            return view('admin/cms-pages/index')->with(['header'=>$header,'pageData'=>$pageData, 'pageDataCount'=>$pageDataCount, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/cms-pages/index')->with(['error'=> $pageListData['message'],'header'=>$header,'pageData'=>$pageData, 'pageDataCount'=>$pageDataCount,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('PAGES','create')){
            return view('admin/401');
        }
        $header['title'] = @trans('Page-Add');
        $page = Page::where('status', 1)->get()->toArray();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/cms-pages/add')->with(['header'=>$header, 'page' => $page]);
    }

    /**
     * Store a newly created pages in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('PAGE','create')){
            return view('admin/401');
        }
    
        $requestData = $request->all();
        $rules = [];

            $customMessages = [];

            $niceNames = array();
        
        $response = Page::createPage($requestData);
        
        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if(!empty($response['data'])){
            return redirect()->route('cms-pages.index')->with('success',$response['message']);
        }else{
            return redirect()->route('cms-pages.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified pages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $header['title'] = 'Page - View';
        $filter = array(
            'id' => $id
        );
        $response = Page::getPages($filter);
        $pageDetail = $response['data'];
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/cms-pages/view')->with(['header'=>$header,'pageData'=>$pageDetail]);
        }else{
            return redirect()->route('cms-pages.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified pages.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('HOME_BANNERS','update')){
            return view('admin/401');
        } 
        $header['title'] = 'Page - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = Page::getPages($filter);
      
        $pageDetail = $response['data'];
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/cms-pages/update')->with(['header'=>$header,'pageDetail'=>$pageDetail]);
        }else{
            return redirect()->route('cms-pages.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified pages in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = NULL) {

        if(!hasPermission('CMS_PAGES','update')){
            return view('admin/401');
        }
        
            $url = $request->only('redirects_to');
            $requestData = $request->all();

            $rules = [
                'editor1' => 'sometimes|required',
            ];

            $customMessages = [
                'required' => 'Page Content is required.',
            ];

            $niceNames = array();

            $this->validate($request, $rules, $customMessages, $niceNames);
            $result = Page::updatePages($requestData);

            $activityLog['request'] = $requestData;
            $activityLog['request_url'] =  request()->url();
            $activityLog['response'] = $result;
            ActiveLog::createBackendActiveLog($activityLog);

            if (!empty($result)) {
            return redirect()->to($url['redirects_to'])->with('success', $result['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $result['message']);
        }
        
    }

    /**
     * Remove the specified pages from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePage(Request $request)
    {
        $url = URL::previous();
        $pageIDs = explode(',', $request->input('delete_page_id'));
        $message = "";
        foreach ($pageIDs as $delete_page_id) {
            $response = Page::deletePage($delete_page_id);
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
     * Check page title exist from page_i18ns database.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPageTitleExist(Request $request) {
        $matchListData = [];
        if (request()->input('page_title') && request()->input('page_title') != "") {
            if (request()->input('id')) {
                $matchListData = PageI18ns::where('page_title', request()->input('page_title'))->where('page_id', '!=', request()->input('id'))->get()->toArray();
            } else {
                $matchListData = PageI18ns::where('page_title', request()->input('page_title'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }
    
     /**
     * Check page slug url exist from page database.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPageSlugURLExist(Request $request) {

        $matchListData = [];
        if (request()->input('slug_url') && request()->input('slug_url') != "") {
            if (request()->input('id')) {
                $matchListData = Page::where('slug_url', request()->input('slug_url'))->where('id', '!=', request()->input('id'))->get()->toArray();
            } else {
                $matchListData = Page::where('slug_url', request()->input('slug_url'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }
    
     /**
     * Upload cms file in the storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadCmsFile(Request $request)
    {
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->file->extension();  
        $request->file->move(storage_path('app/public/cms_images'), $imageName);

        chmod(storage_path("app/public/cms_images/{$imageName}"), 0777);

        if (file_exists(storage_path("app/public/cms_images/{$imageName}"))) {
            return response()->json(['location' => url('/').'/storage/cms_images/'.$imageName]);
        } else {
            return response()->json(['error' => 'File upload failed'], 500);
        }
    }
}
