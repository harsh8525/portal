<?php
/**
 * CountryImport class responsible for importing country data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryI18ns;
use DB;
use Exception;
use Date;
use Carbon\Carbon;

class CountryImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['iso_code'] && $row['country_name_english'] && $row['country_name_arabic']){
                $country_name_english = $row['country_name_english'];
                $country_name_arabic = $row['country_name_arabic'];
                $countryData = Country::withTrashed()
                ->select('id', 'iso_code', 'isd_code', 'max_mobile_number_length', 'status')
                ->with(['countryCode' => function ($country) {
                    $country->select(['country_id', 'country_name','language_code']);
                }])
                ->whereHas('countryCode', function ($q) use ($country_name_english, $country_name_arabic) {
                    $q->where(function($query) use ($country_name_english, $country_name_arabic) {
                        $query->where('country_name', $country_name_english)
                            ->orWhere('country_name', $country_name_arabic);
                    });
                })
                ->orWhere('iso_code', $row['iso_code'])
                ->first();

                if (!$countryData) {

                    $isdCode = $row['isd_code'];
            
                    if (substr($isdCode, 0, 1) !== '+') {
                        $isdCode = '+' . $isdCode;
                    }
        
                    $countryData = Country::create([
                        'iso_code' => $row['iso_code'],
                        'isd_code' => $isdCode,
                        'max_mobile_number_length' => $row['max_mobile_number_length'],
                        'status' => strtolower($row['status']) == 'active' ? 'active' : 'inactive',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
        
                    if ($countryData) {
                        CountryI18ns::create([
                            'country_id' => $countryData->id,
                            'country_name' => $row['country_name_english'],
                            'language_code' => "en",
                        ]);
                        CountryI18ns::create([
                            'country_id' => $countryData->id,
                            'country_name' => $row['country_name_arabic'],
                            'language_code' => "ar",
                        ]);
                    }
                }
            }
        }
    }

    public function withValidator($validator)
    {
        $processedRows = [];
        $validator->after(function ($validator) {
            $emptyData = $validator->getData();
            if (empty($emptyData)) {
                $validator->errors()->add('', 'The Excel sheet is empty.');
                return;
            }

            $fields = [
                'iso_code' => 'ISO code',
                'isd_code' => 'ISD code',
                'max_mobile_number_length' => 'Max Mobile Number Length',
                'country_name_english' => 'Country name english',
                'country_name_arabic' => 'Country name arabic',
            ];

            foreach ($validator->getData() as $key => $data) {
                foreach ($fields as $field => $label) {
                    if (array_key_exists($field, $data)) {
                        $value = $data[$field];

                    } else {
                        
                        $validator->errors()->add('',"key's not exists in the Excel file.");
                        return;
                    }
                }
            }
        });
    }
    
    public function rules(): array
    {
        return ([
        ]);
    }
}
