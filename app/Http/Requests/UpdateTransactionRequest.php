<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.01|max:999999999',
            'occurred_at' => 'required|date',
            'wallet_id' => [
                'required',
                Rule::exists('wallets', 'id')->where('user_id', auth()->id())
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereNull('user_id')->orWhere('user_id', auth()->id());
                })
            ],
            'type' => 'required|in:expense,income',
            'merchant' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->category_id && $this->type) {
                $category = Category::find($this->category_id);
                if ($category && $category->type !== $this->type) {
                    $validator->errors()->add('type', 'Transaction type must match category type.');
                }
            }
        });
    }
}
