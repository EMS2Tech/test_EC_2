<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your auth logic
    }

    public function rules()
    {
        return [
            'payment_slip' => 'required|file|mimes:pdf,png,jpg,jpeg|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'payment_slip.required' => 'Please upload a payment slip.',
            'payment_slip.mimes' => 'Only PDF, PNG, and JPG files are allowed.',
            'payment_slip.max' => 'The file size must not exceed 4MB.',
        ];
    }
}