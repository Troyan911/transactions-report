<?php

namespace App\Services;

use App\Models\TransactionType;
use App\Services\Contracts\ImportCsvServiceContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class ImportCsvService implements ImportCsvServiceContract
{
    /**
     * @return int
     *
     * @throws Exception
     * @throws UnavailableStream
     */
    public function importTransactions(string $filepath)
    {
        $csv = Reader::createFromPath($filepath, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $batchSize = 1000;
        $dataBatch = [];

        DB::table('transactions')->truncate();
        DB::beginTransaction();

        $now = Carbon::now();
        try {
            foreach ($records as $record) {
                $dataBatch[] = [
                    'id' => $record['id'],
                    'timestamp' => $record['timestamp'],
                    'type' => $record['type'],
                    'amount' => $record['amount'],
                    'created_at' => $now,
                    'updated_at' => $now,
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
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    public function importTransactionTypes(string $filepath)
    {
        $csv = Reader::createFromPath($filepath, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        DB::table('transaction_types')->truncate();
        DB::beginTransaction();
        $header = $csv->getHeader();

        try {
            foreach ($records as $record) {
                $row = array_combine($header, $record);
                TransactionType::create($row);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e->getMessage());

            return 1;
        }

        return 0;
    }

}
