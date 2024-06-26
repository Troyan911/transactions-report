<?php

namespace App\Console\Commands;

use App\Services\Contracts\ImportCsvServiceContract;
use Illuminate\Console\Command;

class ImportTransactionsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:transactions {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a CSV file into the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ImportCsvServiceContract $csvService)
    {
        $path = $this->argument('path');
        logs()->error($path);

        if (! file_exists($path)) {
            $this->error("File csv not found at path: {$path}");

            return 1;
        }

        $result = $csvService->importTransactions($path);

        if ($result == 0) {
            $this->info('File imported successfully!');
        } else {
            $this->error('Failed to import file. See logs for details.');
        }

        return $result;
    }
}
