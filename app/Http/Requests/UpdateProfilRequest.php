<?php

namespace App\Http\Requests;

use App\Enums\StatusType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProfilRequest extends FormRequest
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
            'firstname' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'image' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'status' => [new Enum(StatusType::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères',
            'lastname.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être au format jpeg, png ou jpg',
            'image.max' => 'L\'image ne doit pas dépasser 2Mo',
            'status.enum' => 'Le status doit être l\'un des suivants : inactif, en attente, actif',
        ];
    }
}
