<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('site_web') && $this->site_web === '') {
            $this->merge(['site_web' => null]);
        }
    }

    public function rules(): array
    {
        $cvRule = $this->routeIs('operator.profile.store')
            ? 'required|file|max:5120|mimes:pdf,doc,docx'
            : 'nullable|file|max:5120|mimes:pdf,doc,docx';

        return [
            'category_id' => 'required|exists:categories,id',
            'bio' => 'nullable|string|max:255',
            'competences' => 'nullable|string|max:65535',
            'experience' => 'nullable|string|max:65535',
            'localisation' => 'nullable|string|max:200',
            'secteur_activite' => 'required|string|max:200',
            'telephone' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'niveau_etude' => 'nullable|in:bac,licence,master,doctorat,autre',
            'contact_visible' => 'sometimes|boolean',
            'photo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'documents.cv' => $cvRule,
            'documents.other.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'secteur_activite.required' => 'Le secteur d\'activité est obligatoire.',
            'photo.image' => 'La photo doit être une image (jpg, png, webp).',
            'documents.cv.required' => 'Votre CV est obligatoire pour la première soumission.',
            'documents.cv.mimes' => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'documents.cv.max' => 'Le CV ne doit pas dépasser 5 Mo.',
            'documents.other.*.max' => 'Chaque document ne doit pas dépasser 5 Mo.',
            'site_web.url' => 'L’URL du site web n’est pas valide.',
        ];
    }
}
