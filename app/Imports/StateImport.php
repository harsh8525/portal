<?php
/**
 * StateImport class responsible for importing state data.
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
use App\Models\State;
use App\Models\StateI18ns;
use App\Models\City;
use App\Models\Country;
use DB;
use Exception;
use Date;
use Carbon\Carbon;

class StateImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['iso_code'] && $row['state_name_english'] && $row['state_name_arabic']){
        
                $isCountryCodeExist = Country::withTrashed()->where('iso_code', trim(strtoupper($row['country_code'])))->first();

                if ($isCountryCodeExist) {

                    $stateData = State::create([
                        'iso_code' => $row['iso_code'],
                        'country_code' => $row['country_code'],
                        'latitude' => $row['latitude'] ?? '',
                        'longitude' => $row['longitude'] ?? '',
                        'status' => strtolower($row['status']) == 'active' ? 'active' : 'inactive',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
        
                    if ($stateData) {
        
                        StateI18ns::create([
                            'state_id' => $stateData->id,
                            'state_name' => $row['state_name_english'],
                            'language_code' => "en",
                        ]);
                        StateI18ns::create([
                            'state_id' => $stateData->id,
                            'state_name' => $row['state_name_arabic'],
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
                'country_code' => 'Country code',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude',
                'state_name_english' => 'State name english',
                'state_name_arabic' => 'State name arabic',
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
