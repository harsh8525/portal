<?php
/**
 * MonthlyCustomerExport class responsible for importing customer data.
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

class MonthlyCustomerExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithDrawings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $filter = array();
    protected $siteLogo, $from_date, $to_date, $transactionDate;
    function __construct($filter, $transactionDate, $fromDate, $toDate)
    {
        $this->filter = $filter;
        $this->siteLogo = @Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?:  URL::asset('assets/images/logo.png');
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        $this->transactionDate = $transactionDate;
    }
    public function collection()
    {
        $monthlyData = Customer::getMasterMonthlyCustomerReport($this->from_date,$this->to_date, false);
        $data = [];
        foreach ($monthlyData as $key => $value) {
            $temp = [
                'sr.no' => ++$key,
                'month' => $value['month'],
                'count' => $value['count'] == 0 ? '0' : $value['count'],
            ];
            array_push($data, $temp);
        }

        return collect($data);
    }
    public function headings(): array
    {
        return [
           
            'Sr.no',
            'Month',
            'Count',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // Styling an entire column.
            'A12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'B12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'C12'  => ['font' => ['size' => 10, 'bold' => true], ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'A'  => ['font' => ['size' => 10],['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'B'  => ['font' => ['size' => 10],['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],
            'C'  => ['font' => ['size' => 10],['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]],

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
        $drawing->setCoordinates('B1');
        $drawing->setOffsetX($xOffset);
        $drawing->setOffsetY(20); // Adjust the Y offset as needed

        return $drawing;
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:C1'; // All headers

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
                $event->sheet->mergeCells('A1:C1')->getRowDimension('1')->setRowHeight(70);

                // merge cells with height, apply css and set message
                $event->sheet->getDelegate()->mergeCells('A2:C2')->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A2:C2')->applyFromArray($styleArray);
                $event->sheet->setCellValue('A2', "MONTHLY LIST CUSTOMER");

                $event->sheet->getDelegate()->mergeCells('A3:C3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:C3')->applyFromArray($styleArray);
                if ($this->transactionDate == 'all_dates') {
                    $event->sheet->setCellValue('A3', "All DATES");
                } else {
                    $event->sheet->setCellValue('A3', "Date : " . $this->from_date . ' - ' . $this->to_date);
                }
                // merge cells with height, apply css and display total amount
                $event->sheet->getDelegate()->mergeCells('A4:C4')->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A4:C4')->applyFromArray($styleArray);
            
                $row = 5;

                //merge cells with height, apply css and display date
                $event->sheet->getDelegate()->mergeCells('A3:C3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:C3')->applyFromArray($styleArray);


                $event->sheet->getDelegate()->mergeCells('A' . $row . ':C' . $row);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(7);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(7);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);

            },
        ];
    }
}
