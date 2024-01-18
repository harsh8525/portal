<?php
/**
 * AirportImport class responsible for importing airport data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Imports;

use App\Models\Airport;
use App\Models\AirportI18ns;
use App\Models\Country;
use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rules\RequiredIf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Date;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class AirportImport implements ToCollection, WithHeadingRow, WithValidation
{

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['iata_code']){

                $isCountryCodeExist = Country::withTrashed()->where('iso_code', trim(strtoupper($row['country_code'])))->first();

                $isCityCodeExist = City::withTrashed()->where('iso_code', trim(strtoupper($row['city_code'])))->first();
        
                if ($isCountryCodeExist && $isCityCodeExist) {

                    $airpotrsData = Airport::withTrashed()->where('iata_code', $row['iata_code'])->first();

                    if (!$airpotrsData) {

                        $airpotrsData = Airport::create([
                            'iata_code' => $row['iata_code'],
                            'city_code' => $row['city_code'],
                            'country_code' => $row['country_code'],
                            'latitude' => $row['latitude'],
                            'longitude' => $row['longitude'],
                            'status' => strtolower($row['status']) == 'active' ? 'active' : 'inactive',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
            
                        if ($airpotrsData) {
            
                            AirportI18ns::create([
                                'airport_id' => $airpotrsData->id,
                                'airport_name' => $row['airport_name_english'],
                                'language_code' => "en",
                            ]);
                            AirportI18ns::create([
                                'airport_id' => $airpotrsData->id,
                                'airport_name' => $row['airport_name_arabic'],
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
                'iata_code' => 'Iata code',
                'country_code' => 'Country code',
                'city_code' => 'City code',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude',
                'airport_name_english' => 'Airport name english',
                'airport_name_arabic' => 'Airport name arabic',
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
