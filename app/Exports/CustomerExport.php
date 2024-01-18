<?php
/**
 * CustomerExport class responsible for importing customer data.
 * This class implements ToCollection, WithHeadingRow, and WithValidation interfaces.
 */
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Booking;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use \Maatwebsite\Excel\Sheet;
use DB;
use URL;
use App\Models\User;
use App\Models\MovieEvent;
use App\Models\Customer;
use App\Models\AppUsers;
use Carbon\Carbon;

class CustomerExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithDrawings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $filter = array();
    protected $siteLogo, $orderTotalAmount, $from_date, $to_date, $orderStatus, $transactionDate,$first_name,$f_name;
    function __construct($filter, $transactionDate, $fromDate, $toDate,$first_name,$f_name)
    {
        $this->filter = $filter;
        $this->siteLogo = @Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?:  URL::asset('assets/images/logo.png');
        $this->orderTotalAmount = Customer::getMasterCustomerReport($this->filter, false, true);
        $this->from_date = dateFunction($fromDate);
        $this->to_date = dateFunction($toDate);
        $this->transactionDate = $transactionDate;
        $this->first_name = $first_name;
        $this->f_name = $f_name;
    }
    public function collection()
    {
        $agencyData = Customer::getMasterCustomerReport($this->filter, false);

        $data = [];
        foreach ($agencyData as $key => $value) {
            $temp = [
                'sr.no' => ++$key,
                'created_at' => Carbon::parse($value->created_at)->format('d-m-Y'),
                'first_name' => ucwords($value->first_name . ' ' . $value->last_name),
                'email' => $value->email,
                'mobile' => ucwords($value->mobile),
            ];
            array_push($data, $temp);
        }

        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Sr.no',
            'Created Date',
            'Customer Name',
            'Email',
            'Mobile Number',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // Styling an entire column.
            'A12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'B12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'C12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'D12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'E12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'F12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'G12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'H12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'A'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'B'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'C'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'D'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'E'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'F'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'G'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'H'  => ['font' => ['size' => 10], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            
        ];
    }
    public function startCell(): string
    {
        return 'A12';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($this->siteLogo);

        // Set the width of the logo
        $logoWidth = 100;
        $drawing->setWidth($logoWidth);

        // Calculate the X offset to center the logo horizontally
        $cellWidth = 120; // Assuming the width of the cell is 120
        $xOffset = ($cellWidth - $logoWidth) / 2;

        // Set the coordinates and offsets
        $drawing->setCoordinates('D1');
        $drawing->setOffsetX($xOffset);
        $drawing->setOffsetY(20); // Adjust the Y offset as needed

        return $drawing;
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers

                // set font and center style for text
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  14
                    ),
                ];


                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);

                // merge cells with height and set logo
                $event->sheet->mergeCells('A1:E1')->getRowDimension('1')->setRowHeight(70);

                // merge cells with height, apply css and set message
                $event->sheet->getDelegate()->mergeCells('A2:E2')->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A2:E2')->applyFromArray($styleArray);
                $event->sheet->setCellValue('A2', "NEW CUSTOMER SIGNUP");

                $event->sheet->getDelegate()->mergeCells('A3:E3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:E3')->applyFromArray($styleArray);
                if ($this->transactionDate == 'all_dates') {
                    $event->sheet->setCellValue('A3', "All DATES");
                } else {
                    $event->sheet->setCellValue('A3', "Date : " . $this->from_date . ' - ' . $this->to_date);
                }
                // merge cells with height, apply css and display total amount
                $event->sheet->getDelegate()->mergeCells('A4:E4')->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A4:E4')->applyFromArray($styleArray);
                
                $row = 5;

                 if(!empty($this->first_name)){
                     // merge cells with height, apply css and display vendor name
                    $event->sheet->getDelegate()->mergeCells('A'.$row.':E'.$row)->getRowDimension($row)->setRowHeight(20);
                    $event->sheet->getDelegate()->getStyle('A'.$row.':E'.$row)->applyFromArray($styleArray);
                    $event->sheet->setCellValue('A'.$row, "Customer Name : ".ucwords($this->f_name));
                    $row += 1;
                 }
                
                //merge cells with height, apply css and display date
                $event->sheet->getDelegate()->mergeCells('A3:E3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:E3')->applyFromArray($styleArray);


                $event->sheet->getDelegate()->mergeCells('A' . $row . ':E' . $row);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(05);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(17);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(28);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(13);

            },
        ];
    }
}
