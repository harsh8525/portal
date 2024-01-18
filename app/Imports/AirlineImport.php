<?php
/**
 * AirlineImport class responsible for importing airline data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Imports;

use App\Models\Airline;
use App\Models\AirlineI18ns;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Exception;
use Date;
use Carbon\Carbon;


class AirlineImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['airline_code'] && $row['airline_name_english'] && $row['airline_name_arabic']){
                $airline_name_english = $row['airline_name_english'];
                $airline_name_arabic = $row['airline_name_arabic'];
                $airlinesData = Airline::withTrashed()
                ->select('id', 'airline_code', 'status')
                ->with(['airlineCodeName' => function ($airline) {
                    $airline->select(['airline_id', 'airline_name','language_code']);
                }])
                ->whereHas('airlineCodeName', function ($q) use ($airline_name_english, $airline_name_arabic) {
                    $q->where(function($query) use ($airline_name_english, $airline_name_arabic) {
                        $query->where('airline_name', $airline_name_english)
                            ->orWhere('airline_name', $airline_name_arabic);
                    });
                })
                ->orWhere('airline_code', $row['airline_code'])
                ->first();

                if (!$airlinesData) {

                    $airlinesData = Airline::create([
                        'airline_code' => $row['airline_code'],
                        'status' => strtolower($row['status']) == 'active' ? 'active' : 'inactive',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
    
                    if ($airlinesData) {
                        AirlineI18ns::create([
                            'airline_id' => $airlinesData->id,
                            'airline_name' => $row['airline_name_english'],
                            'language_code' => "en",
                        ]);
                        AirlineI18ns::create([
                            'airline_id' => $airlinesData->id,
                            'airline_name' => $row['airline_name_arabic'],
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
                'airline_code' => 'Airline code',
                'airline_name_english' => 'Airline name english',
                'airline_name_arabic' => 'Airline name arabic',
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
