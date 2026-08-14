<?php

namespace App\Support;

use App\Models\Job;

/**
 * Données structurées schema.org/JobPosting.
 *
 * C'est ce que Google lit pour faire apparaître une offre dans Google for
 * Jobs — le principal canal d'acquisition d'un site d'emploi. Sans ce bloc,
 * les offres n'existent pas pour lui.
 *
 * Construit ici plutôt que dans la vue : c'est du contenu structuré à
 * vérifier, pas de la mise en page, et un JSON mal formé fait échouer
 * l'indexation en silence.
 */
class JobPostingSchema
{
    /**
     * Correspondance entre les types de contrat du site et le vocabulaire
     * schema.org, qui n'accepte qu'une liste fermée de valeurs.
     */
    private const EMPLOYMENT_TYPES = [
        'CDI' => 'FULL_TIME',
        'CDD' => 'TEMPORARY',
        'Stage' => 'INTERN',
        'Alternance' => 'PART_TIME',
        'Freelance' => 'CONTRACTOR',
        'Interim' => 'TEMPORARY',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function for(Job $job): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => $job->description,
            'datePosted' => ($job->posted_at ?? $job->created_at)?->toIso8601String(),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $job->company,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $job->location,
                    'addressCountry' => 'SN',
                ],
            ],
        ];

        if ($type = self::EMPLOYMENT_TYPES[$job->type] ?? null) {
            $schema['employmentType'] = $type;
        }

        if ($job->expires_at) {
            $schema['validThrough'] = $job->expires_at->toIso8601String();
        }

        // Ne rien inventer : une fourchette absente vaut mieux qu'une
        // fourchette fausse, que Google confronte aux offres concurrentes.
        if ($job->salary_min || $job->salary_max) {
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'XOF',
                'value' => array_filter([
                    '@type' => 'QuantitativeValue',
                    'minValue' => $job->salary_min,
                    'maxValue' => $job->salary_max,
                    'unitText' => 'MONTH',
                ], fn ($value) => $value !== null),
            ];
        }

        return $schema;
    }
}
