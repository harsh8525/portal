<?php
/**
 * CategoryExport class responsible for importing category data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Exports;

use App\Models\Category;
use App\Models\CategoryDiscount;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use DB;


class CategoryExport implements FromCollection,WithHeadings,WithStyles,WithCustomStartCell,WithEvents
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
        
        $categoryData = Category::getCategoryExportData($this->filter,false);
        
            $data = [];
            foreach($categoryData as $key=>$value)
            {
                $temp =[
                    
                    'id' => $value->id,
                    'name' => ucwords($value->name),
                    'parent_id' => ucwords($value->parent_category_name),
                    'is_parent' => $value->is_parent_text,
                    'description' => strip_tags($value->description),
                    'sort_order' => $value->sort_order ?: '0',
                    'status' => $value->category_status_text, 
                    'dealer' => ($value->dealer == '1') ? 'yes' : 'no', 
                    'exclusive_dealer' => ($value->exclusive_dealer == '1') ? 'yes' : 'no',
                    'channel_partner' => ($value->channel_partner == '1') ? 'yes' : 'no',
                    'distributor' => ($value->distributor == '1') ? 'yes' : 'no',
                    'contractor' => ($value->contractor == '1') ? 'yes' : 'no',
                    'meta_title' => $value->meta_title,
                    'meta_description' => $value->meta_description,
                    'slug_url' => $value->slug_url,
                    'keywords' => $value->keywords,
                    'discount_base' => $value->discount_base_text,
                    'dealer_min_val' => $value->dealer_min_val,
                    'dealer_min_disc' => $value->dealer_min_disc,
                    'dealer_max_val' => $value->dealer_max_val,
                    'dealer_max_disc' => $value->dealer_max_disc,
                    'ex_dealer_min_val' => $value->ex_dealer_min_val,
                    'ex_dealer_min_disc' => $value->ex_dealer_min_disc,
                    'ex_dealer_max_val' => $value->ex_dealer_max_val,
                    'ex_dealer_max_disc' => $value->ex_dealer_max_disc,
                    'channel_partner_min_val' => $value->channel_partner_min_val,
                    'channel_partner_min_disc' => $value->channel_partner_min_disc,
                    'channel_partner_max_val' => $value->channel_partner_max_val,
                    'channel_partner_max_disc' => $value->channel_partner_max_disc,
                    'dist_min_val' => $value->dist_min_val,
                    'dist_min_disc' => $value->dist_min_disc,
                    'dist_max_val' => $value->dist_max_val,
                    'dist_max_disc' => $value->dist_max_disc,
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
            
            'Categroy Id',
            'Category Name',
            'Parent Category Name',
            'Is Parent',
            'Description',
            'Sort Order',
            'Status',
            'Dealer',
            'Exclusive Dealer',
            'Channel Partner',
            'Distributor',
            'Contractor',
            'Meta Title',
            'Meta Description',
            'Slug URl',
            'KeyWords',
            'Amount/Quantity',
            'Dealer Min Value',
            'Dealer Min Discount',
            'Dealer Max Value',
            'Dealer Max Discount',
            'Exclusive Dealer Min Value',
            'Exclusive Dealer Min Discount',
            'Exclusive Dealer Max Value',
            'Exclusive Dealer Max Discount',
            'Channel Partner Min value',
            'Channel Partner Min Discount',
            'Channel Partner Max value',
            'Channel Partner Max Discount',
            'Distributor Min Value',
            'Distributor Min Discount',
            'Distributor Max Value',
            'Distributor Max Discount',
            'Created Date',
            'Updated Date',
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
                $cellRange = 'A1:AE1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(5);
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
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AC')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AD')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AE')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AF')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AG')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AH')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AI')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('AJ')->setWidth(10);


            },
        ];
    }
}
