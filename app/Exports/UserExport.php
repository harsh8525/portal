<?php
/**
 * UserExport class responsible for importing user data.
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
use App\Models\AppUsers;
use Carbon\Carbon;

class UserExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithDrawings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $filter = array();
    protected $siteLogo, $orderTotalAmount, $from_date, $to_date, $orderStatus, $transactionDate,$user_status,$user_name,$agency_name;
    function __construct($filter, $transactionDate, $fromDate, $toDate,$user_status,$user_name,$agency_name)
    {
        $this->filter = $filter;
        $this->siteLogo = @Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?:  URL::asset('assets/images/logo.png');
        $this->orderTotalAmount = User::getMasterUserReport($this->filter, false, true);
        $this->from_date = dateFunction($fromDate);
        $this->to_date = dateFunction($toDate);
        $this->transactionDate = $transactionDate;

        $this->agency_name = $agency_name;
        $this->user_name = $user_name;
        $this->user_status = $user_status;
    }
    public function collection()
    {
        $agencyData = User::getMasterUserReport($this->filter, false);

        $data = [];
        foreach ($agencyData as $key => $value) {
            $code =$value['getRole']->name??'';
            $temp = [
                'sr.no' => ++$key,
                'full_name' => ucwords($value->name),
                'email' => $value->email,
                'email' => $value->email,
                'mobile' => $value->mobile,
                'role_code' => $code,
                'status' => $value->user_status_text    ,
            ];
            array_push($data, $temp);
        }

        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Sr.no',
            'Full Name',
            'Email',
            'Mobile Number',
            'Role',
            'Status',

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
        $cellWidth = 100; // Assuming the width of the cell is 120
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
                $cellRange = 'A1:F1'; // All headers

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
                $event->sheet->mergeCells('A1:F1')->getRowDimension('1')->setRowHeight(70);

                // merge cells with height, apply css and set message
                $event->sheet->getDelegate()->mergeCells('A2:F2')->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A2:F2')->applyFromArray($styleArray);
                $event->sheet->setCellValue('A2', "USER LIST REPORT");

                $event->sheet->getDelegate()->mergeCells('A3:F3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:F3')->applyFromArray($styleArray);
                if ($this->transactionDate == 'all_dates') {
                    $event->sheet->setCellValue('A3', "All DATES");
                } else {
                    $event->sheet->setCellValue('A3', "Date : " . $this->from_date . ' - ' . $this->to_date);
                }
                // merge cells with height, apply css and display total amount
                $event->sheet->getDelegate()->mergeCells('A4:F4')->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A4:F4')->applyFromArray($styleArray);

                $row = 5;
                if(!empty($this->agency_name)){
                    // merge cells with height, apply css and display vendor name
                   $event->sheet->getDelegate()->mergeCells('A'.$row.':F'.$row)->getRowDimension($row)->setRowHeight(20);
                   $event->sheet->getDelegate()->getStyle('A'.$row.':F'.$row)->applyFromArray($styleArray);
                   $event->sheet->setCellValue('A'.$row, "Agency Name : ".ucwords($this->agency_name));
                   $row += 1;
                }
                if(!empty($this->user_name)){
                   // merge cells with height, apply css and display vendor name
                  $event->sheet->getDelegate()->mergeCells('A'.$row.':F'.$row)->getRowDimension($row)->setRowHeight(20);
                  $event->sheet->getDelegate()->getStyle('A'.$row.':F'.$row)->applyFromArray($styleArray);
                  $event->sheet->setCellValue('A'.$row, "User Name : ".ucwords($this->user_name));
                  $row += 1;
               }
                
                if(!empty($this->user_status)){
                    // merge cells with height, apply css and display vendor name
                   $event->sheet->getDelegate()->mergeCells('A'.$row.':F'.$row)->getRowDimension($row)->setRowHeight(20);
                   $event->sheet->getDelegate()->getStyle('A'.$row.':F'.$row)->applyFromArray($styleArray);
                   $event->sheet->setCellValue('A'.$row, "User Status : ".ucwords($this->user_status));
                   $row += 1;
                }
                //merge cells with height, apply css and display date
                $event->sheet->getDelegate()->mergeCells('A3:F3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:F3')->applyFromArray($styleArray);


                $event->sheet->getDelegate()->mergeCells('A' . $row . ':F' . $row);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(13);

            },
        ];
    }
}
