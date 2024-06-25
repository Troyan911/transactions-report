<?php

namespace App\Http\Requests;

use App\Models\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'timestamp' => ['required', 'date', 'before_or_equal:'.Carbon::now()->toDateTimeString()],
            'type' => ['required', 'string', 'exists:'.TransactionType::class.',type'],
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }
}
