<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Handled by policy in controller
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:125',
            'description' => 'required|string',
            'type' => 'required|in:maison,appartement,terrain,local',
            'surface' => 'required|numeric|min:0',
            'prix' => 'required|numeric|min:0',
            'nb_pieces' => 'nullable|integer|min:0',
            'adresse' => 'required|string|max:125',
            'ville' => 'required|string|max:125',
            'nature' => 'required|in:vente,location',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
