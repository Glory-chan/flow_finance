<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->route('card')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'card_holder' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'expiry_date' => [
                'required',
                'string',
                'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                'after:today'
            ],
            'alias' => [
                'nullable',
                'string',
                'max:100'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'card_holder.required' => 'Le nom du titulaire est requis.',
            'card_holder.regex' => 'Le nom du titulaire ne doit contenir que des lettres et des espaces.',
            
            'expiry_date.required' => 'La date d\'expiration est requise.',
            'expiry_date.regex' => 'La date d\'expiration doit être au format MM/AA.',
            'expiry_date.after' => 'La carte ne peut pas être expirée.',
            
            'alias.max' => 'L\'alias ne peut pas dépasser 100 caractères.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'card_holder' => strtoupper(trim($this->card_holder)),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validation personnalisée pour la date d'expiration
            if ($this->expiry_date) {
                $parts = explode('/', $this->expiry_date);
                if (count($parts) === 2) {
                    $month = (int) $parts[0];
                    $year = 2000 + (int) $parts[1];
                    $expiryDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
                    
                    if ($expiryDate->isPast()) {
                        $validator->errors()->add('expiry_date', 'Cette carte est expirée.');
                    }
                }
            }
        });
    }
}