<?php
/**
 * CityImport class responsible for importing city data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Imports;

use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\City;
use App\Models\CityI18n;
use App\Models\Country;
use Date;
use Carbon\Carbon;
use DB;
use Exception;

class CityImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['iso_code']){
                $isCountryCodeExist = Country::withTrashed()->where('iso_code', trim(strtoupper($row['country_code'])))->first();
      
                if ($isCountryCodeExist) {

                    $citiesData = City::withTrashed()->where('iso_code', $row['iso_code'])->first();

                    if (!$citiesData) {
                        $citiesData = City::create([
                            'iso_code' => $row['iso_code'],
                            'country_code' => $row['country_code'],
                            'latitude' => $row['latitude'],
                            'longitude' => $row['longitude'],
                            'status' => strtolower($row['status']) == 'active' ? 'active' : 'inactive',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
            
                        if ($citiesData) {
            
                            CityI18n::create([
                                'city_id' => $citiesData->id,
                                'city_name' => $row['city_name_english'],
                                'language_code' => "en",
                            ]);
                            CityI18n::create([
                                'city_id' => $citiesData->id,
                                'city_name' => $row['city_name_arabic'],
                                'language_code' => "ar",
                            ]);
                        }
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
                'country_code' => 'Country code',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude',
                'city_name_english' => 'City name english',
                'city_name_arabic' => 'City name arabic',
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
