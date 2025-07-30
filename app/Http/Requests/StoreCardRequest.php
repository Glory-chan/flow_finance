<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'card_number' => [
                'required',
                'string',
                'min:13',
                'max:19',
                'regex:/^[0-9\s]+$/',
                'unique:cards,card_number_hash'
            ],
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
            'cvv' => [
                'required',
                'string',
                'digits_between:3,4'
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
            'card_number.required' => 'Le numéro de carte est requis.',
            'card_number.min' => 'Le numéro de carte doit contenir au moins 13 chiffres.',
            'card_number.max' => 'Le numéro de carte ne peut pas dépasser 19 chiffres.',
            'card_number.regex' => 'Le numéro de carte ne doit contenir que des chiffres et des espaces.',
            'card_number.unique' => 'Cette carte est déjà enregistrée.',
            
            'card_holder.required' => 'Le nom du titulaire est requis.',
            'card_holder.regex' => 'Le nom du titulaire ne doit contenir que des lettres et des espaces.',
            
            'expiry_date.required' => 'La date d\'expiration est requise.',
            'expiry_date.regex' => 'La date d\'expiration doit être au format MM/AA.',
            'expiry_date.after' => 'La carte ne peut pas être expirée.',
            
            'cvv.required' => 'Le code CVV est requis.',
            'cvv.digits_between' => 'Le code CVV doit contenir 3 ou 4 chiffres.',
            
            'alias.max' => 'L\'alias ne peut pas dépasser 100 caractères.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'card_number' => preg_replace('/\s+/', '', $this->card_number),
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

            // Validation du numéro de carte avec l'algorithme de Luhn
            if ($this->card_number && !$this->validateLuhn($this->card_number)) {
                $validator->errors()->add('card_number', 'Le numéro de carte n\'est pas valide.');
            }
        });
    }

    /**
     * Validate card number using Luhn algorithm.
     */
    private function validateLuhn(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $sum = 0;
        $alternate = false;

        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = (int) $cardNumber[$i];

            if ($alternate) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit = ($digit % 10) + 1;
                }
            }

            $sum += $digit;
            $alternate = !$alternate;
        }

        return ($sum % 10) === 0;
    }
}