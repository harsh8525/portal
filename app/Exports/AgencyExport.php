<?php
/**
 * AgencyExport class responsible for importing agency data.
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
use App\Models\Agency;
use App\Models\AppUsers;

class AgencyExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithDrawings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $filter = array();
    protected $siteLogo, $orderTotalAmount, $from_date, $to_date, $transactionDate, $agency_type, $agency_name, $agency_status, $agencyType;
    function __construct($filter, $transactionDate, $fromDate, $toDate, $agency_type, $agency_name, $agency_status, $agencyType)
    {
        $this->filter = $filter;
        $this->siteLogo = @Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?:  URL::asset('assets/images/logo.png');
        $this->orderTotalAmount = Agency::getMasterAgencyReport($this->filter, false, true);
        $this->from_date = dateFunction($fromDate);
        $this->to_date = dateFunction($toDate);
        $this->transactionDate = $transactionDate;
        $this->agency_type = @$agency_type;
        $this->agency_name = @$agency_name;
        $this->agency_status = @$agency_status;
        $this->agencyType = $agencyType;
    }
    public function collection()
    {
        $agencyData = Agency::getMasterAgencyReport($this->filter, false);

        $data = [];
        foreach ($agencyData as $key => $value) {

            if (is_null($value->getMasterUserAgencyReport)) {
               $operatoeName = '';
            } else {
                $operatoeName = $value->getMasterUserAgencyReport[0]->name ?? '';
            }
            $temp = [
                'sr.no' => ++$key,
                'full_name' => ucwords($value->full_name),
                'short_name' => ucwords($value->short_name),
                'name' => ucwords($value->getAgencyType[0]->name) ?? '',
                'email' => $value->email,
                'phone_no' => ucwords($value->phone_no),
                'operator_name' => $operatoeName ?? '',
                'status' => ucwords($value->status),
            ];
            array_push($data, $temp);
        }
        return collect($data);
    }
    public function headings(): array
    {
        return [
            'Sr.no',
            'Agency Name',
            'Short Name',
            'Agency Type',
            'Email',
            'Phone Number',
            'Operator Full Name',
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
        $cellWidth = 120; // Assuming the width of the cell is 120
        $xOffset = ($cellWidth - $logoWidth) / 2;

        // Set the coordinates and offsets
        $drawing->setCoordinates('E1');
        $drawing->setOffsetX($xOffset);
        $drawing->setOffsetY(20); // Adjust the Y offset as needed

        return $drawing;
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers

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

                $event->sheet->mergeCells('A1:H1')->getRowDimension('1')->setRowHeight(90); // Increase the row height as needed
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);

                // merge cells with height, apply css and set message
                $event->sheet->getDelegate()->mergeCells('A2:H2')->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A2:H2')->applyFromArray($styleArray);
                $event->sheet->setCellValue('A2', "AGENCY LIST");

                $event->sheet->getDelegate()->mergeCells('A3:H3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray($styleArray);
                if ($this->transactionDate == 'all_dates') {
                    $event->sheet->setCellValue('A3', "All DATES");
                } else {
                    $event->sheet->setCellValue('A3', "Date : " . $this->from_date . ' - ' . $this->to_date);
                }
                // merge cells with height, apply css and display total amount
                $event->sheet->getDelegate()->mergeCells('A4:H4')->getRowDimension('4')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A4:H4')->applyFromArray($styleArray);

                $row = 5;
                if (!empty($this->agency_name)) {
                    // merge cells with height, apply css and display vendor name
                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':H' . $row)->getRowDimension($row)->setRowHeight(20);
                    $event->sheet->getDelegate()->getStyle('A' . $row . ':H' . $row)->applyFromArray($styleArray);
                    $event->sheet->setCellValue('A' . $row, "Agency Name : " . ucwords($this->agency_name));
                    $row += 1;
                }
                if (!empty($this->agency_type)) {
                    // merge cells with height, apply css and display vendor name
                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':H' . $row)->getRowDimension($row)->setRowHeight(20);
                    $event->sheet->getDelegate()->getStyle('A' . $row . ':H' . $row)->applyFromArray($styleArray);
                    $event->sheet->setCellValue('A' . $row, "Agency Type : " . ucwords($this->agencyType));
                    $row += 1;
                }

                if (!empty($this->agency_status)) {
                    // merge cells with height, apply css and display vendor name
                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':H' . $row)->getRowDimension($row)->setRowHeight(20);
                    $event->sheet->getDelegate()->getStyle('A' . $row . ':H' . $row)->applyFromArray($styleArray);
                    $event->sheet->setCellValue('A' . $row, "Agency Status : " . ucwords($this->agency_status));
                    $row += 1;
                }
                //merge cells with height, apply css and display date
                $event->sheet->getDelegate()->mergeCells('A3:H3')->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray($styleArray);


                $event->sheet->getDelegate()->mergeCells('A' . $row . ':H' . $row);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(23);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);

            },
        ];
    }
}
