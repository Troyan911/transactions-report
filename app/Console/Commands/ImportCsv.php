<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {path}';

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
    public function handle()
    {
        $path = $this->argument('path');
        logs()->error($path);

        if (! file_exists($path)) {
            $this->error("File csv not found at path: {$path}");

            return 1;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $batchSize = 1000;
        $dataBatch = [];

        DB::table('transactions')->truncate();
        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                $dataBatch[] = [
                    'id' => $record['id'],
                    'timestamp' => $record['timestamp'],
                    'type' => $record['type'],
                    'amount' => $record['amount'],
                ];

                if (count($dataBatch) == $batchSize) {
                    DB::table('transactions')->insert($dataBatch);
                    $dataBatch = [];
                }
            }

            if (! empty($dataBatch)) {
                DB::table('transactions')->insert($dataBatch);
            }

            DB::commit();
            $this->info('File imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to import file: '.$e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * @return void
     *
     * @throws Exception
     * @throws UnavailableStream
     */
    public function handle2()
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error("File csv not found at path: {$path}");

            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $rows = $csv->getRecords();

        $header = $csv->getHeader();

        DB::table('transactions')->truncate();
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                Transaction::create($row);

                //                $transaction_type = TransactionType::where('type', $row['type'])->first();
                //
                //                if (!$transaction_type) {
                //                    throw new \Exception("Transaction type not found: " . $row['type']);
                //                }
                //
                //                Transaction::create([
                //                    'id' => $row['id'],
                //                    'timestamp' => $row['timestamp'],
                //                    'type_id' => $transaction_type->id,
                //                    'amount' => $row['amount'],
                //                ]);
            }

            DB::commit();
            $this->info('File imported successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('File import error!'.$e->getMessage());
        }
    }

    public function rowInsert()
    {

    }

    private function batchInsert()
    {
        $path = $this->argument('path');

        if (! Storage::exists($path)) {
            $this->error("File not found at path: {$path}");

            return;
        }

        $csv = Reader::createFromPath(Storage::path($path), 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $batchSize = 1000; // Розмір пакету
        $dataBatch = [];

        foreach ($records as $record) {
            $dataBatch[] = [
                'column1' => $record['column1'],
                'column2' => $record['column2'],
                // Інші колонки
            ];

            if (count($dataBatch) == $batchSize) {
                DB::table('your_table')->insert($dataBatch);
                $dataBatch = [];
            }
        }

        // Вставка залишків
        if (! empty($dataBatch)) {
            DB::table('your_table')->insert($dataBatch);
        }

        $this->info('File imported successfully!');
    }
}
