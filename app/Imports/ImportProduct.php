<?php
/**
 * ImportProduct class responsible for importing product data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rules\RequiredIf;
use Date;
use Carbon\Carbon;

class ImportProduct implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {

        $priceTypes = array('1' => 'Per Pcs', '2' => 'Per Pair', '3' => 'Per Set', '4' => 'Per Mtr', '5' => 'Per Ft', '6' => 'Per Roll', '7' => 'Per Kg', '8' => 'Per Box', '9' => 'Per Length', '10' => 'Per Stripe');
        foreach ($rows as $row) {
            $key = array_search(ucwords($row['price_type']), $priceTypes);
            if ($key !== false) {
                $priceType = $key;
            }
            if ($row['slug_url'] != "") {
                $slugURL = strtolower($row['slug_url']);
            } else {
                $slugURL = "";
            }
            $product = Product::create([
                'category_id' => $row['child_category_id'],
                'name' => ucwords($row['product_name']),
                'code' => ucwords($row['product_code']),
                'description' => $row['description'],
                'image' => 'aaa',
                'video_link' => $row['video_link'],
                'pdf_link' => $row['pdf_link'],
                'sort_order' => $row['sort_order'],
                'gst' => $row['gst'],
                'status' => (strtolower($row['status']) == 'active') ? '1' : '0',
                'hot_products' => (strtolower($row['hot_products']) == 'yes') ? '1' : '0',
                'is_drawer_system' => (strtolower($row['is_drawer_system']) == 'yes') ? '1' : '0',
                'box_weight' => $row['box_weigth'],
                'extra_info' => $row['extra_information'],
                'packing_detail' => $row['packing_details'],
                'price_type' => $priceType,
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'slug_url' => $slugURL,
                'keywords' => $row['keywords'],
                'dealer' => (strtolower($row['visible_for_dealer']) == 'yes') ? '1' : '0',
                'exclusive_dealer' => (strtolower($row['visible_for_exclusive_dealer']) == 'yes') ? '1' : '0',
                'channel_partner' => (strtolower($row['visible_for_channel_partner']) == 'yes') ? '1' : '0',
                'distributor' => (strtolower($row['visible_for_distributor']) == 'yes') ? '1' : '0',
                'contractor' => (strtolower($row['visible_for_contractor']) == 'yes') ? '1' : '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $arrayData = [];

            foreach ($validator->getData() as $key => $data) {
                if (isset($data['child_category_id'])) {
                    $tempArray = ['child_category_id' => $data['child_category_id'], 'product_code' => $data['product_code']];
                    if (in_array($tempArray, $arrayData)) {
                        $validator->errors()->add($key, 'product code with same category duplicate entry not allowed');
                    }
                    array_push($arrayData, $tempArray);

                    if (isset($data['pdf_link']) && $data['pdf_link'] != "") {
                        if (!preg_match("/^.*\.(pdf|PDF)$/", $data['pdf_link'])) {
                            $validator->errors()->add($key, 'invalid pdf link');
                        }
                    }
                    if ($data['child_category_id'] != "") {

                        if (Str::contains($data['child_category_id'], ',')) {
                            $validator->errors()->add($key, 'Child Category does not contain a comma.');
                        }
                    }
                    $isExist = Category::where('id', $data['child_category_id'])->where('is_parent', '0')->where('parent_id', '>', '0')->first();
                    if (!empty($isExist)) {
                        $isChildCategory = Category::where('id', $isExist['parent_id'])->where('is_parent', '1')->first();
                        if (empty($isChildCategory)) {
                            $validator->errors()->add($key, 'Category id must be Child Category');
                        }
                    } else {
                        $validator->errors()->add($key, 'Category id must be Child Category');
                    }

                    $isSameCatCodeExist = Product::where('code', $data['product_code'])->where('category_id', $data['child_category_id'])->get()->toArray();
                    if (!empty($isSameCatCodeExist)) {
                        $validator->errors()->add($key, 'product code with same category is already exist');
                    }
                }
            }
        });
    }
    public function rules(): array
    {
        Validator::extend('checkCategoryExist', function ($attribute, $value) {
   
            $category_data = Category::where('id', $value)->where('is_parent', '0')->get();

            if ($category_data->count() > 0) {
                return true;
            }
            return false; 
        }, 'Child Category does not exist');
        Validator::extend('checkPriceTypeExist', function ($attribute, $value) {
            $checkPriceTypes = array('1' => 'Per Pcs', '2' => 'Per Pair', '3' => 'Per Set', '4' => 'Per Mtr', '5' => 'Per Ft', '6' => 'Per Roll', '7' => 'Per Kg', '8' => 'Per Box', '9' => 'Per Length', '10' => 'Per Stripe');
            foreach ($checkPriceTypes as $key => $type) {
                if (strtolower($value) == strtolower($type)) {
                    return true;
                }
            }
            return false;
        }, 'In-valid Price Type');
        return ([
            'child_category_id' => 'required|checkCategoryExist',
            'product_name' => 'required',
            'product_code' => 'required',
            'description' => 'max:300',
            'video_link' => 'nullable|url',
            'pdf_link' => 'nullable',

            'sort_order' => 'required|numeric',
            'gst' => 'required|in:0,5,12,18',
            'status' => 'required',
            'hot_products' => 'in:yes,no,Yes,No',
            'is_drawer_system' => 'in:yes,no,Yes,No',
            'price_type' => 'required|checkPriceTypeExist',
            'meta_title' => '',
            'meta_description' => 'max:300',
            'slug_url' => 'nullable|unique:products,slug_url|regex:/^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$/',
        ]);
    }
}
