<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Commune;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CommuneSeeder::class);

        $admin = User::updateOrCreate([
            'name' => 'Administrateur',
            'email' => 'admin@commune.bj',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();
        $admin->managedCommunes()->sync(Commune::query()->pluck('id'));

        $cotonou = Commune::where('name', 'Cotonou')->first();

        User::updateOrCreate([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password123'),
            'role' => 'operateur',
            'commune_id' => $cotonou?->id,
            'is_active' => true,
        ])->forceFill(['email_verified_at' => now()])->save();

        $categories = [
            ['name' => 'Cadres administratifs', 'description' => 'Personnels et cadres des administrations publiques ou assimilées.'],
            ['name' => 'Cadres techniques', 'description' => 'Ingénieurs, techniciens et experts de terrain.'],
            ['name' => 'Chefs d\'entreprise', 'description' => 'Dirigeants, gérants et responsables de structures.'],
            ['name' => 'Artisans', 'description' => 'Métiers manuels, artisanat et production locale.'],
            ['name' => 'Commerçants', 'description' => 'Commerce de détail, gros et distribution.'],
            ['name' => 'Jeunes entrepreneurs', 'description' => 'Porteurs de projets et startups en phase de lancement ou de croissance.'],
            ['name' => 'Investisseurs / partenaires', 'description' => 'Financements, partenariats stratégiques et accompagnement.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }

        $demoProfiles = [
            ['name' => 'Koffi Mensah', 'email' => 'seed.profil.01@communepro.bj', 'category' => 'Cadres administratifs', 'secteur' => 'Administration publique locale', 'localisation' => 'Cotonou', 'bio' => 'Cadre au service des collectivités, gestion de projets communautaires.'],
            ['name' => 'Aminata Diallo', 'email' => 'seed.profil.02@communepro.bj', 'category' => 'Cadres techniques', 'secteur' => 'Génie civil & infrastructures', 'localisation' => 'Porto-Novo', 'bio' => 'Ingénieure ; études et suivi de chantiers pour le développement local.'],
            ['name' => 'Roméo Agbodjelou', 'email' => 'seed.profil.03@communepro.bj', 'category' => 'Chefs d\'entreprise', 'secteur' => 'PME / services aux entreprises', 'localisation' => 'Cotonou', 'bio' => 'Dirigeant d’une structure de conseil et d’accompagnement des TPE.'],
            ['name' => 'Fatou N\'Guessan', 'email' => 'seed.profil.04@communepro.bj', 'category' => 'Artisans', 'secteur' => 'Menuiserie & ameublement', 'localisation' => 'Abomey-Calavi', 'bio' => 'Artisan menuisier ; fabrication sur mesure et rénovation.'],
            ['name' => 'Sènami Hounkpatin', 'email' => 'seed.profil.05@communepro.bj', 'category' => 'Commerçants', 'secteur' => 'Commerce de détail alimentaire', 'localisation' => 'Parakou', 'bio' => 'Commerçant de proximité ; produits locaux et import.'],
            ['name' => 'Ornella Sossou', 'email' => 'seed.profil.06@communepro.bj', 'category' => 'Jeunes entrepreneurs', 'secteur' => 'Tech & solutions numériques', 'localisation' => 'Cotonou', 'bio' => 'Jeune entrepreneure ; applications et services en ligne pour les PME.'],
            ['name' => 'Ibrahim Bio Seka', 'email' => 'seed.profil.07@communepro.bj', 'category' => 'Investisseurs / partenaires', 'secteur' => 'Investissement & mentorat', 'localisation' => 'Cotonou', 'bio' => 'Accompagnement de projets et mise en relation avec des financeurs.'],
            ['name' => 'Gisèle Hounguè', 'email' => 'seed.profil.08@communepro.bj', 'category' => 'Cadres administratifs', 'secteur' => 'Ressources humaines publiques', 'localisation' => 'Lokossa', 'bio' => 'Spécialiste RH dans le secteur public.'],
            ['name' => 'Kodjo Assogba', 'email' => 'seed.profil.09@communepro.bj', 'category' => 'Artisans', 'secteur' => 'Métallurgie & soudure', 'localisation' => 'Ouidah', 'bio' => 'Chaudronnier ; portails, grilles et structures métalliques.'],
            ['name' => 'Valentine Dossou', 'email' => 'seed.profil.10@communepro.bj', 'category' => 'Commerçants', 'secteur' => 'Textile & habillement', 'localisation' => 'Cotonou', 'bio' => 'Commerçante ; prêt-à-porter et accessoires.'],
        ];

        foreach ($demoProfiles as $index => $row) {
            $category = Category::where('name', $row['category'])->firstOrFail();
            $n = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $commune = Commune::where('name', $row['localisation'])->first();

            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'operateur',
                    'commune_id' => $commune?->id,
                    'is_active' => true,
                ]
            );
            if ($user->email_verified_at === null) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
            if ($user->commune_id === null && $commune) {
                $user->update(['commune_id' => $commune->id]);
            }

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'category_id' => $category->id,
                    'bio' => $row['bio'],
                    'competences' => 'Communication, gestion de projet, relation client.',
                    'experience' => 'Plusieurs années d’activité dans le secteur.',
                    'localisation' => $row['localisation'],
                    'secteur_activite' => $row['secteur'],
                    'telephone' => '+229 01 90 '.$n.' 00 '.$n,
                    'niveau_etude' => 'licence',
                    'status' => 'approved',
                    'contact_visible' => true,
                ]
            );
        }
    }
}
