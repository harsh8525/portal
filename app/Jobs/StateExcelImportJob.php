<?php
/**
 * StateExcelImportJob class responsible for handling the asynchronous import of state data from Excel.
 * This class implements ShouldQueue interface for queuable jobs.
 */
namespace App\Jobs;

use App\Imports\StateImport;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Session;

class StateExcelImportJob implements ShouldQueue
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
    public function handle()
    {
        try {

            $filePath = $this->file;
            Excel::import(new StateImport, $filePath);

            Session::flash('success', 'State Excel File Imported Successfuly');

            Log::info('State excel sheet imported successfully.');

            
        } catch (ValidationException $e) {
            $failures = $e->failures(); // Access validation errors
            Session::flash('failures', $failures);
        }
    }
}
