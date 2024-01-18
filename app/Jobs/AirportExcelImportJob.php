<?php
/**
 * AirportExcelImportJob class responsible for handling the asynchronous import of airport data from Excel.
 * This class implements ShouldQueue interface for queuable jobs.
 */
namespace App\Jobs;

use App\Imports\AirportImport;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Session;

class AirportExcelImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $filePath = $this->file;
            Excel::import(new AirportImport, $filePath);

            Session::flash('success', 'Airport Excel File Imported Successfuly');

            Log::info('Airport excel sheet imported successfuly.');
        } catch (ValidationException $e) {
            $failures = $e->failures(); // Access validation errors
            Session::flash('failures', $failures);
        }
    }
}
