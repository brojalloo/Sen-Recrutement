<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des recruteurs
        $recruiters = [
            [
                'name' => 'TechCorp Solutions',
                'email' => 'recruiter1@techcorp.sn',
                'password' => Hash::make('password'),
                'first_name' => 'Amadou',
                'last_name' => 'Diallo',
                'phone' => '+221 77 123 45 67',
                'role' => 'recruiter',
                'status' => 'active',
                'company_name' => 'TechCorp Solutions',
                'company_description' => 'Leader dans le développement de solutions informatiques innovantes en Afrique de l\'Ouest. Nous accompagnons les entreprises dans leur transformation digitale.',
            ],
            [
                'name' => 'Digital Senegal',
                'email' => 'recruiter2@digitalsenegal.sn',
                'password' => Hash::make('password'),
                'first_name' => 'Fatou',
                'last_name' => 'Seck',
                'phone' => '+221 77 234 56 78',
                'role' => 'recruiter',
                'status' => 'active',
                'company_name' => 'Digital Senegal',
                'company_description' => 'Agence digitale spécialisée dans le marketing digital, le développement web et mobile. Plus de 10 ans d\'expérience.',
            ],
            [
                'name' => 'Innovation Hub',
                'email' => 'recruiter3@innovhub.sn',
                'password' => Hash::make('password'),
                'first_name' => 'Moussa',
                'last_name' => 'Ndiaye',
                'phone' => '+221 77 345 67 89',
                'role' => 'recruiter',
                'status' => 'active',
                'company_name' => 'Innovation Hub',
                'company_description' => 'Incubateur et accélérateur de startups technologiques. Nous soutenons les entrepreneurs africains dans la réalisation de leurs projets innovants.',
            ],
        ];

        foreach ($recruiters as $recruiterData) {
            User::create($recruiterData);
        }

        // Créer des candidats
        $candidates = [
            [
                'name' => 'Ibrahima Kane',
                'email' => 'candidate1@email.com',
                'password' => Hash::make('password'),
                'first_name' => 'Ibrahima',
                'last_name' => 'Kane',
                'phone' => '+221 76 111 22 33',
                'role' => 'candidate',
                'status' => 'active',
                'address' => 'Dakar, Plateau',
                'bio' => 'Développeur Full Stack passionné avec 5 ans d\'expérience en Laravel, Vue.js et React. Spécialisé dans le développement d\'applications web et mobiles.',
            ],
            [
                'name' => 'Aïssatou Mbaye',
                'email' => 'candidate2@email.com',
                'password' => Hash::make('password'),
                'first_name' => 'Aïssatou',
                'last_name' => 'Mbaye',
                'phone' => '+221 76 222 33 44',
                'role' => 'candidate',
                'status' => 'active',
                'address' => 'Dakar, Almadies',
                'bio' => 'Designer UX/UI créative avec une forte expertise en design thinking et création d\'expériences utilisateur innovantes. Portfolio disponible sur demande.',
            ],
            [
                'name' => 'Omar Sarr',
                'email' => 'candidate3@email.com',
                'password' => Hash::make('password'),
                'first_name' => 'Omar',
                'last_name' => 'Sarr',
                'phone' => '+221 76 333 44 55',
                'role' => 'candidate',
                'status' => 'active',
                'address' => 'Dakar, Mermoz',
                'bio' => 'Chef de projet digital avec 7 ans d\'expérience dans la gestion de projets web et mobile. Certifié PMP et Scrum Master.',
            ],
            [
                'name' => 'Mariama Fall',
                'email' => 'candidate4@email.com',
                'password' => Hash::make('password'),
                'first_name' => 'Mariama',
                'last_name' => 'Fall',
                'phone' => '+221 76 444 55 66',
                'role' => 'candidate',
                'status' => 'active',
                'address' => 'Dakar, Sacré-Cœur',
                'bio' => 'Data Scientist spécialisée en Machine Learning et Intelligence Artificielle. Maîtrise Python, R et TensorFlow.',
            ],
            [
                'name' => 'Cheikh Sy',
                'email' => 'candidate5@email.com',
                'password' => Hash::make('password'),
                'first_name' => 'Cheikh',
                'last_name' => 'Sy',
                'phone' => '+221 76 555 66 77',
                'role' => 'candidate',
                'status' => 'active',
                'address' => 'Dakar, Point E',
                'bio' => 'Développeur Mobile expert en développement d\'applications natives iOS et Android. Connaissance approfondie de Swift et Kotlin.',
            ],
        ];

        foreach ($candidates as $candidateData) {
            User::create($candidateData);
        }

        // Récupérer les IDs des recruteurs créés
        $recruiter1 = User::where('email', 'recruiter1@techcorp.sn')->first();
        $recruiter2 = User::where('email', 'recruiter2@digitalsenegal.sn')->first();
        $recruiter3 = User::where('email', 'recruiter3@innovhub.sn')->first();

        // Créer des offres d'emploi
        $jobs = [
            [
                'recruiter_id' => $recruiter1->id,
                'title' => 'Développeur Full Stack Laravel',
                'description' => 'Nous recherchons un développeur Full Stack expérimenté en Laravel et Vue.js pour rejoindre notre équipe dynamique.',
                'company' => 'TechCorp Solutions',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 800000,
                'salary_max' => 1500000,
                'experience' => '3-5 ans',
                'skills' => 'Laravel, Vue.js, MySQL, Git, API REST',
                'requirements' => "- Maîtrise de Laravel 10+\n- Expérience avec Vue.js ou React\n- Connaissance des bases de données relationnelles\n- Bon niveau en français et anglais",
                'benefits' => "- Salaire compétitif\n- Assurance santé\n- Formation continue\n- Télétravail partiel possible",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter1->id,
                'title' => 'Développeur Mobile Flutter',
                'description' => 'Rejoignez notre équipe pour développer des applications mobiles innovantes avec Flutter.',
                'company' => 'TechCorp Solutions',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 700000,
                'salary_max' => 1200000,
                'experience' => '2-4 ans',
                'skills' => 'Flutter, Dart, Firebase, API REST',
                'requirements' => "- Expérience en développement mobile Flutter\n- Connaissance de Dart\n- Maîtrise de Firebase\n- Portfolio d'applications publiées",
                'benefits' => "- Environnement de travail moderne\n- Projets innovants\n- Tickets restaurant\n- Prime de performance",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter2->id,
                'title' => 'Designer UX/UI Senior',
                'description' => 'Créez des expériences utilisateur exceptionnelles pour nos clients prestigieux.',
                'company' => 'Digital Senegal',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 900000,
                'salary_max' => 1600000,
                'experience' => '4-6 ans',
                'skills' => 'Figma, Adobe XD, Sketch, Design Thinking, Prototypage',
                'requirements' => "- Portfolio professionnel exigé\n- Maîtrise de Figma et Adobe Creative Suite\n- Expérience en design thinking\n- Excellentes compétences en communication",
                'benefits' => "- Équipement Mac fourni\n- Budget formation\n- Horaires flexibles\n- Participation aux conférences",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter2->id,
                'title' => 'Chef de Projet Digital',
                'description' => 'Pilotez des projets digitaux ambitieux de A à Z pour des clients internationaux.',
                'company' => 'Digital Senegal',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 1000000,
                'salary_max' => 1800000,
                'experience' => '5-8 ans',
                'skills' => 'Gestion de projet, Scrum, Agile, JIRA, Budget',
                'requirements' => "- Certification PMP ou Scrum Master\n- Expérience en gestion de projets web/mobile\n- Maîtrise des méthodologies Agile\n- Leadership et gestion d'équipe",
                'benefits' => "- Package salarial attractif\n- Voiture de fonction\n- Bonus sur objectifs\n- Évolution de carrière",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter3->id,
                'title' => 'Data Scientist',
                'description' => 'Exploitez la puissance des données pour aider nos startups à prendre les bonnes décisions.',
                'company' => 'Innovation Hub',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 1200000,
                'salary_max' => 2000000,
                'experience' => '3-5 ans',
                'skills' => 'Python, Machine Learning, TensorFlow, SQL, Data Visualization',
                'requirements' => "- Master en Data Science ou équivalent\n- Maîtrise de Python et bibliothèques ML\n- Expérience en modélisation prédictive\n- Compétences en visualisation de données",
                'benefits' => "- Environnement startup dynamique\n- Participation aux événements tech\n- Stock-options\n- Remote work friendly",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter3->id,
                'title' => 'Développeur Backend Node.js',
                'description' => 'Développez des APIs robustes et scalables pour nos applications innovantes.',
                'company' => 'Innovation Hub',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDD',
                'salary_min' => 600000,
                'salary_max' => 1000000,
                'experience' => '2-3 ans',
                'skills' => 'Node.js, Express, MongoDB, Docker, API REST',
                'requirements' => "- Solide expérience en Node.js\n- Connaissance de MongoDB\n- Maîtrise de Git\n- Expérience avec Docker (un plus)",
                'benefits' => "- Contrat 12 mois renouvelable\n- Ambiance startup\n- Mentorat senior\n- Équipement fourni",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(1),
            ],
            [
                'recruiter_id' => $recruiter1->id,
                'title' => 'Administrateur Système Linux',
                'description' => 'Assurez la gestion et la maintenance de notre infrastructure serveurs.',
                'company' => 'TechCorp Solutions',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 750000,
                'salary_max' => 1300000,
                'experience' => '3-5 ans',
                'skills' => 'Linux, Docker, Kubernetes, AWS, Monitoring',
                'requirements' => "- Expertise en administration Linux\n- Connaissance de Docker et Kubernetes\n- Expérience avec AWS ou Azure\n- Compétences en automatisation",
                'benefits' => "- Formation certifications\n- Garde téléphonique rémunérée\n- Mutuelle famille\n- Évolution vers DevOps",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'recruiter_id' => $recruiter2->id,
                'title' => 'Community Manager',
                'description' => 'Animez les réseaux sociaux de nos clients et créez de l\'engagement.',
                'company' => 'Digital Senegal',
                'location' => 'Dakar, Sénégal',
                'type' => 'CDI',
                'salary_min' => 400000,
                'salary_max' => 700000,
                'experience' => '1-3 ans',
                'skills' => 'Réseaux sociaux, Content Marketing, Canva, Analytics',
                'requirements' => "- Excellente maîtrise du français\n- Créativité et sens de la communication\n- Connaissance des réseaux sociaux\n- Maîtrise d'outils de design basiques",
                'benefits' => "- Ambiance jeune et dynamique\n- Formation continue\n- Prime sur objectifs\n- Télétravail possible",
                'status' => 'open',
                'is_active' => true,
                'posted_at' => now(),
                'expires_at' => now()->addMonth(),
            ],
        ];

        // `approval_status` n'était jamais renseigné : toutes les offres
        // restaient en attente et la démonstration s'ouvrait sur une page
        // d'accueil vide. Les deux dernières restent volontairement en
        // attente, pour que la file de modération de l'admin ait de quoi
        // montrer.
        $pendingCount = 2;
        $lastApproved = count($jobs) - $pendingCount;

        foreach ($jobs as $index => $jobData) {
            $jobData['approval_status'] = $index < $lastApproved ? 'approved' : 'pending';

            Job::create($jobData);
        }

        // Créer quelques candidatures
        $candidate1 = User::where('email', 'candidate1@email.com')->first();
        $candidate2 = User::where('email', 'candidate2@email.com')->first();
        $candidate3 = User::where('email', 'candidate3@email.com')->first();
        $candidate4 = User::where('email', 'candidate4@email.com')->first();

        $job1 = Job::where('title', 'Développeur Full Stack Laravel')->first();
        $job2 = Job::where('title', 'Designer UX/UI Senior')->first();
        $job3 = Job::where('title', 'Chef de Projet Digital')->first();
        $job4 = Job::where('title', 'Data Scientist')->first();

        if ($job1 && $candidate1) {
            Application::create([
                'job_id' => $job1->id,
                'user_id' => $candidate1->id,
                'cover_letter' => 'Bonjour, je suis très intéressé par ce poste de développeur Full Stack. Mon expérience de 5 ans en Laravel et Vue.js correspond parfaitement aux exigences du poste.',
                'status' => 'pending',
                'applied_at' => now()->subDays(2),
            ]);
        }

        if ($job2 && $candidate2) {
            Application::create([
                'job_id' => $job2->id,
                'user_id' => $candidate2->id,
                'cover_letter' => 'Passionnée par le design UX/UI, je serais ravie de mettre mes compétences au service de Digital Senegal. Mon portfolio démontre ma capacité à créer des expériences utilisateur innovantes.',
                'status' => 'pending',
                'applied_at' => now()->subDays(1),
            ]);
        }

        if ($job3 && $candidate3) {
            Application::create([
                'job_id' => $job3->id,
                'user_id' => $candidate3->id,
                'cover_letter' => 'Fort de mes 7 ans d\'expérience en gestion de projet et de ma certification PMP, je peux apporter une réelle valeur ajoutée à vos projets digitaux.',
                'status' => 'accepted',
                'applied_at' => now()->subDays(5),
            ]);
        }

        if ($job4 && $candidate4) {
            Application::create([
                'job_id' => $job4->id,
                'user_id' => $candidate4->id,
                'cover_letter' => 'Data Scientist passionnée, j\'ai travaillé sur plusieurs projets d\'IA et de Machine Learning. Mon expertise en Python et TensorFlow sera un atout pour vos startups.',
                'status' => 'pending',
                'applied_at' => now()->subHours(12),
            ]);
        }

        $this->command->info('✅ Données de démonstration créées avec succès!');
        $this->command->info('📧 Connexion candidat: candidate1@email.com / password');
        $this->command->info('📧 Connexion recruteur: recruiter1@techcorp.sn / password');
    }
}
