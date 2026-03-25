<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules(): array
    {
        return [
            'category_id'      => 'required|exists:categories,id',
            'bio'              => 'nullable|string|max:500',
            'competences'      => 'nullable|string',
            'experience'       => 'nullable|string',
            'localisation'     => 'required|string|max:200',
            'secteur_activite' => 'required|string|max:200',
            'telephone'        => 'nullable|string|max:20',
            'site_web'         => 'nullable|url',
            'niveau_etude'     => 'nullable|in:bac,licence,master,doctorat,autre',
            'photo'            => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'documents[cv]'    => 'required|file|max:5120|mimes:pdf,doc,docx',
            'documents[other].*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'localisation.required' => 'La localisation est obligatoire.',
            'secteur_activite.required' => 'Le secteur d\'activité est obligatoire.',
            'photo.image'          => 'La photo doit être une image (jpg, png, webp).',
            'documents[cv].required' => 'Votre CV est obligatoire.',
            'documents[cv].mimes'  => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'documents[cv].max'    => 'Le CV ne doit pas dépasser 5 Mo.',
            'documents[other].*.max' => 'Chaque document ne doit pas dépasser 5 Mo.',
        ];
    }
}
