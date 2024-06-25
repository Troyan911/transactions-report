<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param CreateTransactionRequest $request
     * @return JsonResponse
     */
    public function __invoke(CreateTransactionRequest $request): JsonResponse
    {
        try {
            $row = $request->validated();
            Transaction::create($row);

            return response()->json([
                'info' => 'Transaction created successfully!',
            ], 200);
        } catch (\Exception $e) {
            logs()->error($e);

            return response()->json([
                'error' => 'Transaction creation failure!',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
