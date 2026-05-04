<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════
        // UTILISATEURS
        // ══════════════════════════════════════════

        $admin = User::create([
            'name'     => 'Karim Benali',
            'email'    => 'karim.benali@devcollab.io',
            'password' => Hash::make('Admin@2024'),
            'role'     => 'admin',
            'theme'    => 'light',
            'language' => 'fr',
        ]);

        $sara = User::create([
            'name'     => 'Sara Moussaoui',
            'email'    => 'sara.moussaoui@devcollab.io',
            'password' => Hash::make('Member@2024'),
            'role'     => 'member',
            'theme'    => 'light',
            'language' => 'fr',
        ]);

        $yassine = User::create([
            'name'     => 'Yassine Tazi',
            'email'    => 'yassine.tazi@devcollab.io',
            'password' => Hash::make('Member@2024'),
            'role'     => 'member',
            'theme'    => 'dark',
            'language' => 'en',
        ]);

        $nadia = User::create([
            'name'     => 'Nadia Alaoui',
            'email'    => 'nadia.alaoui@devcollab.io',
            'password' => Hash::make('Member@2024'),
            'role'     => 'member',
            'theme'    => 'light',
            'language' => 'fr',
        ]);

        $omar = User::create([
            'name'     => 'Omar Chakir',
            'email'    => 'omar.chakir@devcollab.io',
            'password' => Hash::make('Member@2024'),
            'role'     => 'member',
            'theme'    => 'light',
            'language' => 'fr',
        ]);

        $ines = User::create([
            'name'     => 'Inès Berrada',
            'email'    => 'ines.berrada@devcollab.io',
            'password' => Hash::make('Member@2024'),
            'role'     => 'member',
            'theme'    => 'dark',
            'language' => 'en',
        ]);

        // ══════════════════════════════════════════
        // PROJET 1 — Application e-commerce
        // ══════════════════════════════════════════

        $p1 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'ShopFlow — Plateforme E-commerce',
            'description' => 'Développement d\'une plateforme e-commerce complète avec gestion des produits, panier, paiement Stripe et tableau de bord vendeur.',
            'status'      => 'active',
        ]);

        $p1->members()->attach([
            $admin->id   => ['role' => 'admin'],
            $sara->id    => ['role' => 'member'],
            $yassine->id => ['role' => 'member'],
            $nadia->id   => ['role' => 'member'],
        ]);

        $tasks_p1 = [
            // TO DO
            [
                'title'       => 'Intégrer le système de paiement Stripe',
                'description' => 'Implémenter Stripe Checkout pour les paiements en ligne. Gérer les webhooks pour confirmer les commandes.',
                'status'      => 'todo',
                'priority'    => 'high',
                'assigned_to' => $yassine->id,
                'due_date'    => '2026-05-10',
                'position'    => 0,
            ],
            [
                'title'       => 'Système de filtres produits avancés',
                'description' => 'Filtres par catégorie, prix, note et disponibilité. Implémentation avec Livewire pour le temps réel.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'assigned_to' => $sara->id,
                'due_date'    => '2026-05-15',
                'position'    => 1,
            ],
            [
                'title'       => 'Page de confirmation de commande',
                'description' => 'Email de confirmation automatique après achat avec récapitulatif de commande et numéro de suivi.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'assigned_to' => $nadia->id,
                'due_date'    => '2026-05-20',
                'position'    => 2,
            ],
            // IN PROGRESS
            [
                'title'       => 'Tableau de bord vendeur',
                'description' => 'Interface de gestion des produits, stocks, commandes et statistiques de vente avec graphiques Chart.js.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'assigned_to' => $admin->id,
                'due_date'    => '2026-05-05',
                'position'    => 0,
            ],
            [
                'title'       => 'Optimisation des performances de la BDD',
                'description' => 'Ajout d\'index sur les colonnes fréquemment requêtées. Mise en cache Redis pour les produits populaires.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'assigned_to' => $yassine->id,
                'due_date'    => '2026-05-03',
                'position'    => 1,
            ],
            // DONE
            [
                'title'       => 'Conception de la base de données',
                'description' => 'Modélisation complète : produits, catégories, commandes, utilisateurs, avis.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $yassine->id,
                'due_date'    => '2026-04-20',
                'position'    => 0,
            ],
            [
                'title'       => 'Authentification et gestion des rôles',
                'description' => 'Login/Register avec Laravel Breeze. Rôles : admin, vendeur, client.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $admin->id,
                'due_date'    => '2026-04-22',
                'position'    => 1,
            ],
            [
                'title'       => 'Design UI des pages produits',
                'description' => 'Maquettes Figma validées et intégrées pour les pages liste et détail produit.',
                'status'      => 'done',
                'priority'    => 'medium',
                'assigned_to' => $sara->id,
                'due_date'    => '2026-04-25',
                'position'    => 2,
            ],
            [
                'title'       => 'Système de gestion du panier',
                'description' => 'Panier persistant en session et en BDD pour les utilisateurs connectés.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $nadia->id,
                'due_date'    => '2026-04-28',
                'position'    => 3,
            ],
        ];

        foreach ($tasks_p1 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p1->id,
                'created_by' => $admin->id,
            ]));
        }

        // ══════════════════════════════════════════
        // PROJET 2 — Application mobile RH
        // ══════════════════════════════════════════

        $p2 = Project::create([
            'owner_id'    => $sara->id,
            'name'        => 'HRConnect — App Mobile RH',
            'description' => 'Application mobile React Native pour la gestion des congés, fiches de paie, notes de frais et annuaire d\'entreprise.',
            'status'      => 'active',
        ]);

        $p2->members()->attach([
            $sara->id    => ['role' => 'admin'],
            $omar->id    => ['role' => 'member'],
            $ines->id    => ['role' => 'member'],
            $admin->id   => ['role' => 'member'],
        ]);

        $tasks_p2 = [
            // TO DO
            [
                'title'       => 'Module de gestion des congés',
                'description' => 'Demande, validation hiérarchique et suivi des congés avec calendrier des absences.',
                'status'      => 'todo',
                'priority'    => 'high',
                'assigned_to' => $omar->id,
                'due_date'    => '2026-05-12',
                'position'    => 0,
            ],
            [
                'title'       => 'Intégration des notifications push',
                'description' => 'Alertes pour validation de congé, publication de fiche de paie et rappels d\'entretien.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'assigned_to' => $ines->id,
                'due_date'    => '2026-05-18',
                'position'    => 1,
            ],
            // IN PROGRESS
            [
                'title'       => 'API REST des fiches de paie',
                'description' => 'Endpoints sécurisés pour consulter et télécharger les bulletins de salaire en PDF.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'assigned_to' => $admin->id,
                'due_date'    => '2026-05-06',
                'position'    => 0,
            ],
            [
                'title'       => 'Annuaire collaborateurs',
                'description' => 'Liste des employés avec photo, poste, département et coordonnées. Recherche en temps réel.',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'assigned_to' => $ines->id,
                'due_date'    => '2026-05-08',
                'position'    => 1,
            ],
            // DONE
            [
                'title'       => 'Architecture React Native + API Laravel',
                'description' => 'Setup du projet mobile, configuration Expo, connexion à l\'API avec Sanctum tokens.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $sara->id,
                'due_date'    => '2026-04-15',
                'position'    => 0,
            ],
            [
                'title'       => 'Authentification SSO entreprise',
                'description' => 'Connexion via Microsoft Azure AD avec OAuth2. Support du 2FA obligatoire.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $omar->id,
                'due_date'    => '2026-04-20',
                'position'    => 1,
            ],
            [
                'title'       => 'Design system mobile',
                'description' => 'Composants UI réutilisables : boutons, formulaires, cartes, navigation. Guide de style Figma.',
                'status'      => 'done',
                'priority'    => 'medium',
                'assigned_to' => $ines->id,
                'due_date'    => '2026-04-24',
                'position'    => 2,
            ],
        ];

        foreach ($tasks_p2 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p2->id,
                'created_by' => $sara->id,
            ]));
        }

        // ══════════════════════════════════════════
        // PROJET 3 — Refonte site institutionnel
        // ══════════════════════════════════════════

        $p3 = Project::create([
            'owner_id'    => $nadia->id,
            'name'        => 'Refonte Site Web — Agence Innova',
            'description' => 'Refonte complète du site institutionnel d\'une agence de communication : nouveau design, CMS headless, performances Core Web Vitals optimisées.',
            'status'      => 'active',
        ]);

        $p3->members()->attach([
            $nadia->id   => ['role' => 'admin'],
            $sara->id    => ['role' => 'member'],
            $omar->id    => ['role' => 'member'],
        ]);

        $tasks_p3 = [
            // TO DO
            [
                'title'       => 'Migration du contenu vers Strapi CMS',
                'description' => 'Export du contenu WordPress existant et import dans Strapi. Mapping des types de contenu.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'assigned_to' => $omar->id,
                'due_date'    => '2026-05-14',
                'position'    => 0,
            ],
            [
                'title'       => 'Optimisation Core Web Vitals',
                'description' => 'LCP < 2.5s, FID < 100ms, CLS < 0.1. Optimisation images WebP, lazy loading, preload fonts.',
                'status'      => 'todo',
                'priority'    => 'high',
                'assigned_to' => $nadia->id,
                'due_date'    => '2026-05-20',
                'position'    => 1,
            ],
            // IN PROGRESS
            [
                'title'       => 'Développement des pages principales',
                'description' => 'Accueil, À propos, Services, Portfolio, Contact. Intégration Next.js avec données Strapi.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'assigned_to' => $sara->id,
                'due_date'    => '2026-05-07',
                'position'    => 0,
            ],
            // DONE
            [
                'title'       => 'Maquettes UI/UX validées',
                'description' => 'Design complet sur Figma. Validation client sur desktop, tablette et mobile.',
                'status'      => 'done',
                'priority'    => 'high',
                'assigned_to' => $nadia->id,
                'due_date'    => '2026-04-18',
                'position'    => 0,
            ],
            [
                'title'       => 'Setup infrastructure Next.js + Strapi',
                'description' => 'Configuration Vercel pour le front, Railway pour Strapi, PostgreSQL pour la BDD.',
                'status'      => 'done',
                'priority'    => 'medium',
                'assigned_to' => $omar->id,
                'due_date'    => '2026-04-22',
                'position'    => 1,
            ],
            [
                'title'       => 'Intégration Google Analytics 4 + Tag Manager',
                'description' => 'Configuration du tracking des événements : formulaires, clics CTA, téléchargements.',
                'status'      => 'done',
                'priority'    => 'low',
                'assigned_to' => $sara->id,
                'due_date'    => '2026-04-26',
                'position'    => 2,
            ],
        ];

        foreach ($tasks_p3 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p3->id,
                'created_by' => $nadia->id,
            ]));
        }

        
        $p4 = Project::create([
            'owner_id'    => $yassine->id,
            'name'        => 'MicroCore — Architecture Microservices',
            'description' => 'Migration de l\'application monolithique vers une architecture microservices avec Docker, Kubernetes et API Gateway.',
            'status'      => 'completed',
        ]);

        $p4->members()->attach([
            $yassine->id => ['role' => 'admin'],
            $admin->id   => ['role' => 'member'],
            $ines->id    => ['role' => 'member'],
        ]);

        $tasks_p4 = [
            ['title' => 'Analyse et découpage du monolithe', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $yassine->id, 'due_date' => '2026-03-10', 'position' => 0],
            ['title' => 'Service d\'authentification JWT', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $admin->id, 'due_date' => '2026-03-20', 'position' => 1],
            ['title' => 'API Gateway avec Kong', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $yassine->id, 'due_date' => '2026-03-28', 'position' => 2],
            ['title' => 'Containerisation Docker de tous les services', 'status' => 'done', 'priority' => 'medium', 'assigned_to' => $ines->id, 'due_date' => '2026-04-05', 'position' => 3],
            ['title' => 'Déploiement Kubernetes (GKE)', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $yassine->id, 'due_date' => '2026-04-15', 'position' => 4],
            ['title' => 'Monitoring avec Grafana + Prometheus', 'status' => 'done', 'priority' => 'medium', 'assigned_to' => $admin->id, 'due_date' => '2026-04-20', 'position' => 5],
        ];

        foreach ($tasks_p4 as $t) {
            Task::create(array_merge($t, [
                'project_id'  => $p4->id,
                'created_by'  => $yassine->id,
                'description' => '',
            ]));
        }

        
        $taskStripe = Task::where('title', 'LIKE', '%Stripe%')->first();
        $taskDashboard = Task::where('title', 'LIKE', '%Tableau de bord vendeur%')->first();
        $taskAPI = Task::where('title', 'LIKE', '%API REST des fiches%')->first();
        $taskPages = Task::where('title', 'LIKE', '%pages principales%')->first();
        $taskConges = Task::where('title', 'LIKE', '%congés%')->first();

        if ($taskStripe) {
            Comment::create([
                'task_id'    => $taskStripe->id,
                'user_id'    => $yassine->id,
                'body'       => 'J\'ai commencé l\'intégration de Stripe Checkout. Le webhook de confirmation de paiement est fonctionnel en local. Je bloque sur la gestion des remboursements partiels — quelqu\'un a de l\'expérience avec ça ?',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ]);
            Comment::create([
                'task_id'    => $taskStripe->id,
                'user_id'    => $admin->id,
                'body'       => 'Oui, utilise `Stripe::refund()` avec le paramètre `amount` pour les remboursements partiels. Je t\'envoie la doc en privé. Pour les webhooks, pense à configurer le secret dans le .env de prod.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);
        }

        if ($taskDashboard) {
            Comment::create([
                'task_id'    => $taskDashboard->id,
                'user_id'    => $sara->id,
                'body'       => 'Les maquettes du dashboard vendeur sont prêtes sur Figma. J\'ai ajouté un graphique de ventes mensuel et un tableau des dernières commandes. @Karim tu peux valider avant que je commence l\'intégration ?',
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ]);
            Comment::create([
                'task_id'    => $taskDashboard->id,
                'user_id'    => $admin->id,
                'body'       => 'Maquettes validées, super travail Sara ! Juste un point : ajoute un indicateur de stock faible (< 10 unités) en rouge sur le tableau des produits. Pour le reste c\'est parfait, go pour l\'intégration.',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ]);
        }

        if ($taskAPI) {
            Comment::create([
                'task_id'    => $taskAPI->id,
                'user_id'    => $admin->id,
                'body'       => 'L\'endpoint GET /api/payslips/{month} est prêt et retourne le PDF en base64. J\'ai aussi ajouté la pagination pour lister les 12 derniers bulletins. Tests Postman disponibles dans le repo.',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ]);
            Comment::create([
                'task_id'    => $taskAPI->id,
                'user_id'    => $ines->id,
                'body'       => 'Parfait ! J\'ai intégré l\'endpoint dans l\'app mobile, ça marche nickel. Le PDF s\'affiche avec react-native-pdf. Par contre le temps de réponse est un peu lent (~1.2s), on peut mettre en cache côté serveur ?',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ]);
        }

        if ($taskPages) {
            Comment::create([
                'task_id'    => $taskPages->id,
                'user_id'    => $sara->id,
                'body'       => 'La page d\'accueil et la page Services sont terminées. La page Portfolio est en cours — j\'attends les photos des projets clients de Nadia pour finaliser la galerie.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]);
            Comment::create([
                'task_id'    => $taskPages->id,
                'user_id'    => $nadia->id,
                'body'       => 'Photos envoyées par email ! J\'en ai 18 au total, toutes optimisées en WebP. J\'ai aussi ajouté les descriptions de chaque projet dans le CMS Strapi, tu peux les récupérer via l\'API.',
                'created_at' => now()->subHours(10),
                'updated_at' => now()->subHours(10),
            ]);
        }

        if ($taskConges) {
            Comment::create([
                'task_id'    => $taskConges->id,
                'user_id'    => $omar->id,
                'body'       => 'Réunion de spec faite avec les RH ce matin. Points importants : les congés doivent être validés en 2 étapes (manager direct puis RH), et il faut gérer les jours fériés marocains automatiquement. Je mets à jour le doc de spécification.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]);
        }

        
        $this->command->info('');
        $this->command->info('✅ Base de données remplie avec succès !');
        $this->command->info('');
        $this->command->info('👤 Comptes disponibles :');
        $this->command->info('   Admin   → karim.benali@devcollab.io    / Admin@2024');
        $this->command->info('   Member  → sara.moussaoui@devcollab.io  / Member@2024');
        $this->command->info('   Member  → yassine.tazi@devcollab.io    / Member@2024');
        $this->command->info('   Member  → nadia.alaoui@devcollab.io    / Member@2024');
        $this->command->info('   Member  → omar.chakir@devcollab.io     / Member@2024');
        $this->command->info('   Member  → ines.berrada@devcollab.io    / Member@2024');
        $this->command->info('');
        $this->command->info('📁 4 projets créés :');
        $this->command->info('   • ShopFlow — Plateforme E-commerce       (actif)');
        $this->command->info('   • HRConnect — App Mobile RH              (actif)');
        $this->command->info('   • Refonte Site Web — Agence Innova       (actif)');
        $this->command->info('   • MicroCore — Architecture Microservices (terminé)');
        $this->command->info('');
        $this->command->info('📋 28 tâches | 💬 7 commentaires | 👥 6 membres');
        $this->command->info('');
    }
}