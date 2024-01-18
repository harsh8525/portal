<?php

/**
 * @package     Pages
 * @subpackage  Page
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Pages.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\PageI18ns;
use App\Models\GeoRegionLists;
use App\Models\Setting;
use App\Traits\AmadeusService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;


class PageController extends BaseController
{


    
    /**
     * @OA\Get(
     ** path="/v1/page/get-pages",
     *   tags={"Pages"},
     *   summary="get page information",
     *   description="get page information<br><br>Need to pass page_code that fetch by call API 'page-code'<br>Need to pass language_code 'en' for English or 'ar' for Arabic.",
     *   operationId="get-page",
     *   @OA\Parameter(
     *       name="body",
     *       in="query",
     *       required=false,
     *       explode=true,
     *       @OA\Schema(
     *            collectionFormat="multi",
     *            required={"page_code"},
     *            @OA\Property(property="page_code", type="string",  ),
     *            @OA\Property(property="language_code", type="string",  ),
     *       ),
     *   ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getPages(Request $request)
    {

        $data = [];
        $requestData = $request->all();
        //set validation for page_code
        $validator = Validator::make($requestData, [
            'page_code' => 'required',
            'language_code' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        try {
            
            
            $page = Page::select('id', 'page_code', 'slug_url', 'status',
            DB::raw('(CASE WHEN pages.status = "0" THEN "In-Active" '
            . 'WHEN pages.status = "1" THEN "Active" '
            . 'END) AS airline_status_text'),
            )
            ->with(['pageCodeName' => function ($page) use($requestData) {
                $page->select(['page_id', 'page_title', 'page_content','meta_title', 'meta_description', 'keywords', 'language_code'])->where('language_code', '=',  $requestData['language_code'] );
            }])
                ->Where('page_code', $requestData['page_code'])
                ->get()
                ->toArray();
                if(!$page) {
                    $success = [];
                    return $this->sendError('Invalid request', $success, 200);
                }
                if(!$page[0]['page_code_name']) {
                    $success = [];
                    return $this->sendError('Invalid request', $success, 200);
                }
                    foreach ($page[0]['page_code_name'] as $pageName) {
                                $page_title = $pageName['page_title'];
                                $page_content = $pageName['page_content'];
                                $meta_title = $pageName['meta_title'];
                                $meta_description = $pageName['meta_description'];
                                $keywords = $pageName['keywords'];
                                $language_code = $pageName['language_code'];
                            }
                    $pageData = [
                        'id' => $page[0]['id'],
                        'page_code' => $page[0]['page_code'],
                        'slug_url' => $page[0]['slug_url'],
                        'page_title' => $page_title,
                        'page_content' => $page_content,
                        'meta_title' => $meta_title,
                        'meta_description' => $meta_description,
                    'keywords' => $keywords,
                    'status' => $page[0]['status'],
                    'airline_status_text' => $page[0]['airline_status_text'],
                ];

            if ($pageData) {
                $success = true;
                return $this->sendResponse($pageData, 'Page Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Page List Not Found', $success, 200);
            }

        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     ** path="/v1/page/page-code",
     *   tags={"Pages"},
     *   summary="get Page Code into application ",
     *   description="get page code list<br><br>",
     *   operationId="page-code",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

     public function getPageCodeDetails(Request $request)
     {
        $getPageCodeData = Page::select('id','page_code','slug_url')->where('status','1')->orderBy('page_code','ASC')->get()->toArray();

        $success = true;
        return $this->sendResponse($getPageCodeData, 'Service types details founded.',$success);
     }
    }