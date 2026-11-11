<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Règles partagées par la création et la modification d'une offre.
 *
 * Elles étaient recopiées dans les deux méthodes du contrôleur, à un champ
 * près : deux listes à maintenir en parallèle, donc deux listes qui finissent
 * par diverger.
 *
 * L'autorisation reste au contrôleur, qui filtre la requête par recruteur
 * avant le `findOrFail` — répondre 404 plutôt que 403 évite de révéler
 * l'existence de l'offre d'un concurrent.
 */
class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'status' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'salary_max.gte' => 'Le salaire maximum ne peut pas être inférieur au salaire minimum.',
        ];
    }
}
