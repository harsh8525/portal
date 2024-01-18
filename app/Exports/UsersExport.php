<?php
/**
 * UsersExport class responsible for importing user data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Exports;

use App\Models\User;
use App\Models\AppUsers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class UsersExport implements FromCollection,WithHeadings,WithStyles,WithCustomStartCell,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filter = array();
    function __construct($filter) {
        $this->filter= $filter;
    }
    public function collection()
    {
        $usersData = Appusers::getAppUserExportData($this->filter,false);

            $data = [];
            foreach($usersData as $key=>$value)
            {
                $temp =[
                    'id' => $value->id,
                    'firm' => ucwords($value->firm),
                    'owner_name' => ucwords($value->owner_name),
                    'mobile' => $value->mobile,
                    'email' => $value->email,
                    'user_type' => $value->user_type_text,
                    'status' => $value->user_status_text, 
                    'distributor' => $value->distributor_name,
                    'ref_distributor' => $value->ref_distributor_name,
                    'ref_dealer' => $value->ref_dealer_name,
                    'website' => $value->website,
                    'company_gst_no' => $value->company_gst_no,
                    'shop_name' => $value->shop_name,
                    'shop_gst_no' => $value->shop_gst_no,
                    'working_city' => $value->working_city,
                    'working_state' => $value->working_state,
                    'deleted_reason' => $value->deleted_reason,
                    'app_user_address_id' => $value->app_user_address_id,
                    'address' => $value->address,
                    'country' => $value->country,
                    'city' => $value->city,
                    'pincode' => $value->pincode,
                    'state' => $value->state,
                    'address_count' => $value->address_count,
                    'created_at' => date("d-m-Y",strtotime($value->created_at)),
                    'updated_at' => date("d-m-Y",strtotime($value->updated_at)),
                ];
                array_push($data,$temp);
            }
            return collect($data);
            
    }
    public function headings():array
    {
        return [
            'User Id','Firm','Owner Name','Mobile','Email','User Type','Status','Distributor','Ref. Distributor','Ref. Dealer','Web Site',
            'Company GST No.','Shop Name','Shop GST No','Working City','Working State','Deleted Reason','User Address Id','Address',
            'Country','City','Pincode','State','Address Count','Created Date','Updated Date'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true,'size' => 15]],

        ];
    }
    
    public function startCell(): string
    {
        return 'A1';
    }

    public function registerEvents(): array {
        
       
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:Z1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(10);
                


            },
        ];
    }
}
