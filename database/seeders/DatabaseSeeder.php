<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════
        // 1. UTILISATEURS — avec rôles, thèmes, skills, job_title
        // ══════════════════════════════════════════════════════

        // ── Admin principal ──
        $admin = User::create([
            'name'      => 'Marwa El Faiz',
            'email'     => 'admin@devcollab.io',
            'password'  => Hash::make('Admin@2024'),
            'role'      => 'admin',
            'theme'     => 'light',
            'language'  => 'fr',
            'job_title' => 'Lead Developer & Chef de Projet',
            'skills'    => 'Laravel, Vue.js, MySQL, Docker, IA',
            'bio'       => 'Fondatrice de DevCollab. Passionnée par l\'architecture logicielle et l\'IA.',
        ]);

        // ── Second admin ──
        $admin2 = User::create([
            'name'      => 'Karim Benali',
            'email'     => 'karim@devcollab.io',
            'password'  => Hash::make('Admin@2024'),
            'role'      => 'admin',
            'theme'     => 'dark',
            'language'  => 'en',
            'job_title' => 'CTO',
            'skills'    => 'Node.js, React, PostgreSQL, AWS, Kubernetes',
            'bio'       => 'Architecte cloud et microservices.',
        ]);

        // ── Membres ──
        $sara = User::create([
            'name'      => 'Sara Moussaoui',
            'email'     => 'sara@devcollab.io',
            'password'  => Hash::make('Member@2024'),
            'role'      => 'member',
            'theme'     => 'light',
            'language'  => 'fr',
            'job_title' => 'UI/UX Designer',
            'skills'    => 'Figma, TailwindCSS, React, HTML, CSS',
            'bio'       => 'Designer spécialisée en expérience utilisateur mobile et web.',
        ]);

        $yassine = User::create([
            'name'      => 'Yassine Tazi',
            'email'     => 'yassine@devcollab.io',
            'password'  => Hash::make('Member@2024'),
            'role'      => 'member',
            'theme'     => 'dark',
            'language'  => 'en',
            'job_title' => 'Backend Developer',
            'skills'    => 'PHP, Laravel, MySQL, Redis, Stripe API',
            'bio'       => 'Expert Laravel et intégrations de paiement.',
        ]);

        $nadia = User::create([
            'name'      => 'Nadia Alaoui',
            'email'     => 'nadia@devcollab.io',
            'password'  => Hash::make('Member@2024'),
            'role'      => 'member',
            'theme'     => 'light',
            'language'  => 'fr',
            'job_title' => 'Full Stack Developer',
            'skills'    => 'Vue.js, Laravel, Docker, Next.js, Strapi',
            'bio'       => 'Développeuse full stack avec expertise DevOps.',
        ]);

        $omar = User::create([
            'name'      => 'Omar Chakir',
            'email'     => 'omar@devcollab.io',
            'password'  => Hash::make('Member@2024'),
            'role'      => 'member',
            'theme'     => 'light',
            'language'  => 'fr',
            'job_title' => 'Mobile Developer',
            'skills'    => 'React Native, Expo, Firebase, REST API',
            'bio'       => 'Spécialiste applications mobiles cross-platform.',
        ]);

        $ines = User::create([
            'name'      => 'Inès Berrada',
            'email'     => 'ines@devcollab.io',
            'password'  => Hash::make('Member@2024'),
            'role'      => 'member',
            'theme'     => 'dark',
            'language'  => 'en',
            'job_title' => 'DevOps Engineer',
            'skills'    => 'Docker, Kubernetes, GitHub Actions, CI/CD, Terraform',
            'bio'       => 'Ingénieure DevOps, automatise tout ce qui peut l\'être.',
        ]);

        $this->command->info('✅ 7 utilisateurs créés (2 admins, 5 membres)');

        // ══════════════════════════════════════════════════════
        // 2. PROJET 1 — DevCollab lui-même (démo parfaite)
        //    Progression : ~67% | Statut : actif
        // ══════════════════════════════════════════════════════

        $p1 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'DevCollab — Plateforme de gestion de projets',
            'description' => 'Développement de la plateforme DevCollab : Kanban board, IA de génération de tâches, chat temps réel, pièces jointes, rôles admin/membre, notifications email.',
            'status'      => 'active',
        ]);

        $p1->members()->attach([
            $admin->id   => ['role' => 'admin'],
            $admin2->id  => ['role' => 'admin'],
            $sara->id    => ['role' => 'member'],
            $yassine->id => ['role' => 'member'],
            $nadia->id   => ['role' => 'member'],
            $ines->id    => ['role' => 'member'],
        ]);

        $tasks_p1 = [
            // ── TO DO ──
            [
                'title'        => 'Intégration GitHub Actions CI/CD',
                'description'  => 'Pipeline automatique : tests PHPUnit, analyse Psalm, déploiement sur staging puis production. Notifications Slack en cas d\'échec.',
                'status'       => 'todo',
                'priority'     => 'high',
                'assigned_to'  => $ines->id,
                'due_date'     => '2026-05-15',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Export PDF des rapports de projet',
                'description'  => 'Générer un rapport complet (tâches, membres, progression, commentaires) en PDF avec DomPDF. Bouton dans la page show du projet.',
                'status'       => 'todo',
                'priority'     => 'medium',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-05-18',
                'position'     => 1,
                'ai_generated' => true,
            ],
            [
                'title'        => 'Mode démo public (read-only)',
                'description'  => 'Page de démonstration publique avec données fictives. Accès sans compte, tout est en lecture seule.',
                'status'       => 'todo',
                'priority'     => 'low',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-05-22',
                'position'     => 2,
                'ai_generated' => true,
            ],
            // ── IN PROGRESS ──
            [
                'title'        => 'Système de notifications email',
                'description'  => 'Envoi d\'emails lors de l\'assignation d\'une tâche, nouveau commentaire, invitation projet. Templates HTML avec Laravel Mail.',
                'status'       => 'in_progress',
                'priority'     => 'high',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-05-08',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Système de pièces jointes sur les tâches',
                'description'  => 'Upload PDF, images, documents. Drag & drop. Preview images. Téléchargement sécurisé. Max 10MB par fichier.',
                'status'       => 'in_progress',
                'priority'     => 'medium',
                'assigned_to'  => $nadia->id,
                'due_date'     => '2026-05-06',
                'position'     => 1,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Calendrier des tâches interactif',
                'description'  => 'Vue calendrier mensuelle avec FullCalendar.js. Affichage des deadlines, drag pour déplacer les dates.',
                'status'       => 'in_progress',
                'priority'     => 'medium',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-05-10',
                'position'     => 2,
                'ai_generated' => true,
            ],
            // ── DONE ──
            [
                'title'        => 'Authentification complète (Login/Register/OAuth)',
                'description'  => 'Laravel Breeze + Google OAuth + GitHub OAuth. Hash bcrypt, remember me, mot de passe oublié.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $admin->id,
                'due_date'     => '2026-04-10',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Kanban Board avec drag & drop',
                'description'  => 'Colonnes To Do / In Progress / Done. SortableJS pour le drag & drop. Mise à jour AJAX du statut et position.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $nadia->id,
                'due_date'     => '2026-04-15',
                'position'     => 1,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Génération de tâches par IA (Groq API)',
                'description'  => 'Intégration Groq LLaMA 3. L\'IA génère 5 tâches adaptées au projet ET les assigne automatiquement selon les compétences des membres.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $admin->id,
                'due_date'     => '2026-04-20',
                'position'     => 2,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Système de rôles Admin / Membre',
                'description'  => 'Admin : CRUD complet, invitations, gestion équipe. Membre : consulter, commenter, déplacer ses tâches. Middleware + Policies.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $admin->id,
                'due_date'     => '2026-04-22',
                'position'     => 3,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Chat temps réel par projet',
                'description'  => 'Messagerie interne par projet. Polling AJAX toutes les 3s. Avatars, horodatage, messages propres vs étrangers.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $admin2->id,
                'due_date'     => '2026-04-25',
                'position'     => 4,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Dashboard Analytics',
                'description'  => 'Graphiques de progression, vélocité par membre, burndown chart. Données dynamiques depuis MySQL.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $admin2->id,
                'due_date'     => '2026-04-28',
                'position'     => 5,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Sidebar collapsible avec tooltips',
                'description'  => 'Animation CSS fluide, icônes toujours visibles en mode réduit, tooltips au hover, état mémorisé en localStorage.',
                'status'       => 'done',
                'priority'     => 'low',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-04-30',
                'position'     => 6,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Système d\'invitation par email',
                'description'  => 'L\'admin invite un membre par email. Lien tokenisé valable 7 jours. Page d\'acceptation avec création de compte directe.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-05-01',
                'position'     => 7,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Alertes deadline (En retard / Bientôt)',
                'description'  => 'Badges colorés sur les cartes Kanban : rouge si en retard, orange si deadline dans 2 jours. Bannière d\'alerte en haut de page.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $nadia->id,
                'due_date'     => '2026-05-02',
                'position'     => 8,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Dark mode + multi-langue (FR/EN)',
                'description'  => 'Thème clair/sombre persistant par utilisateur. Traduction complète de l\'interface en français et anglais.',
                'status'       => 'done',
                'priority'     => 'low',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-05-03',
                'position'     => 9,
                'ai_generated' => false,
            ],
        ];

        foreach ($tasks_p1 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p1->id,
                'created_by' => $admin->id,
            ]));
        }

        $this->command->info('✅ Projet 1 créé — DevCollab (15 tâches)');

        // ══════════════════════════════════════════════════════
        // 3. PROJET 2 — E-commerce
        //    Progression : ~44% | Statut : actif | En retard !
        // ══════════════════════════════════════════════════════

        $p2 = Project::create([
            'owner_id'    => $admin2->id,
            'name'        => 'ShopFlow — Plateforme E-commerce',
            'description' => 'Développement d\'une boutique en ligne complète : catalogue produits, panier, paiement Stripe, espace vendeur avec statistiques temps réel.',
            'status'      => 'active',
        ]);

        $p2->members()->attach([
            $admin2->id  => ['role' => 'admin'],
            $yassine->id => ['role' => 'member'],
            $sara->id    => ['role' => 'member'],
            $nadia->id   => ['role' => 'member'],
        ]);

        $tasks_p2 = [
            // ── TO DO ──
            [
                'title'        => 'Système de filtres produits avancés',
                'description'  => 'Filtres par catégorie, prix, note, disponibilité. Temps réel avec Alpine.js. URL partageable avec paramètres.',
                'status'       => 'todo',
                'priority'     => 'medium',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-05-20',
                'position'     => 0,
                'ai_generated' => true,
            ],
            [
                'title'        => 'Emails transactionnels (confirmation, expédition)',
                'description'  => 'Templates HTML responsive. Email de confirmation de commande, avis d\'expédition avec numéro de suivi.',
                'status'       => 'todo',
                'priority'     => 'medium',
                'assigned_to'  => $nadia->id,
                'due_date'     => '2026-05-25',
                'position'     => 1,
                'ai_generated' => true,
            ],
            [
                'title'        => 'Programme fidélité et codes promo',
                'description'  => 'Système de points, niveaux client (Bronze/Silver/Gold), codes de réduction à usage limité.',
                'status'       => 'todo',
                'priority'     => 'low',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-06-01',
                'position'     => 2,
                'ai_generated' => true,
            ],
            // ── IN PROGRESS — tâche EN RETARD pour la démo ──
            [
                'title'        => 'Intégration Stripe Checkout + webhooks',
                'description'  => 'Paiement sécurisé par carte. Webhooks pour confirmer commandes et gérer remboursements. Test avec Stripe CLI.',
                'status'       => 'in_progress',
                'priority'     => 'high',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-04-30', // PASSÉ → en retard
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Tableau de bord vendeur',
                'description'  => 'KPIs : CA journalier/mensuel, commandes en attente, produits à stock faible. Graphiques Chart.js.',
                'status'       => 'in_progress',
                'priority'     => 'high',
                'assigned_to'  => $admin2->id,
                'due_date'     => '2026-05-05',
                'position'     => 1,
                'ai_generated' => false,
            ],
            // ── DONE ──
            [
                'title'        => 'Modélisation base de données',
                'description'  => 'Tables : products, categories, orders, order_items, reviews, coupons, users.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $yassine->id,
                'due_date'     => '2026-04-20',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Authentification et rôles (admin/vendeur/client)',
                'description'  => 'Laravel Breeze. Middleware pour chaque rôle. Espace admin séparé de la boutique.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $admin2->id,
                'due_date'     => '2026-04-22',
                'position'     => 1,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Design UI pages produits et panier',
                'description'  => 'Maquettes Figma validées. Intégration TailwindCSS. Responsive mobile first.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-04-26',
                'position'     => 2,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Système de panier persistant',
                'description'  => 'Panier en session pour visiteurs, synchronisé en BDD à la connexion.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $nadia->id,
                'due_date'     => '2026-04-28',
                'position'     => 3,
                'ai_generated' => false,
            ],
        ];

        foreach ($tasks_p2 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p2->id,
                'created_by' => $admin2->id,
            ]));
        }

        $this->command->info('✅ Projet 2 créé — ShopFlow (9 tâches)');

        // ══════════════════════════════════════════════════════
        // 4. PROJET 3 — App Mobile RH
        //    Progression : ~57% | Statut : actif
        // ══════════════════════════════════════════════════════

        $p3 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'HRConnect — Application Mobile RH',
            'description' => 'App React Native pour la gestion RH : congés, fiches de paie, notes de frais, annuaire d\'entreprise. Backend Laravel avec API Sanctum.',
            'status'      => 'active',
        ]);

        $p3->members()->attach([
            $admin->id  => ['role' => 'admin'],
            $omar->id   => ['role' => 'member'],
            $ines->id   => ['role' => 'member'],
            $sara->id   => ['role' => 'member'],
        ]);

        $tasks_p3 = [
            // ── TO DO ──
            [
                'title'        => 'Module gestion des congés',
                'description'  => 'Demande en ligne, validation 2 niveaux (manager + RH), historique, solde restant. Jours fériés marocains automatiques.',
                'status'       => 'todo',
                'priority'     => 'high',
                'assigned_to'  => $omar->id,
                'due_date'     => '2026-05-12',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Notifications push React Native',
                'description'  => 'Firebase Cloud Messaging. Alertes : validation congé, fiche de paie disponible, rappel entretien.',
                'status'       => 'todo',
                'priority'     => 'medium',
                'assigned_to'  => $ines->id,
                'due_date'     => '2026-05-18',
                'position'     => 1,
                'ai_generated' => true,
            ],
            // ── IN PROGRESS ──
            [
                'title'        => 'API REST fiches de paie (PDF)',
                'description'  => 'GET /api/payslips/{month} retourne PDF base64. Liste des 12 derniers bulletins. Cache Redis 24h.',
                'status'       => 'in_progress',
                'priority'     => 'high',
                'assigned_to'  => $admin->id,
                'due_date'     => '2026-05-07',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Annuaire collaborateurs',
                'description'  => 'Liste employés avec photo, poste, département, coordonnées. Recherche temps réel, filtre par département.',
                'status'       => 'in_progress',
                'priority'     => 'medium',
                'assigned_to'  => $ines->id,
                'due_date'     => '2026-05-09',
                'position'     => 1,
                'ai_generated' => false,
            ],
            // ── DONE ──
            [
                'title'        => 'Setup React Native + Expo + API Laravel',
                'description'  => 'Configuration projet mobile, Expo Router, Sanctum token auth, intercepteur Axios.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $omar->id,
                'due_date'     => '2026-04-15',
                'position'     => 0,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Authentification SSO Azure AD + 2FA',
                'description'  => 'OAuth2 Microsoft, 2FA obligatoire via TOTP, tokens refresh automatique.',
                'status'       => 'done',
                'priority'     => 'high',
                'assigned_to'  => $ines->id,
                'due_date'     => '2026-04-20',
                'position'     => 1,
                'ai_generated' => false,
            ],
            [
                'title'        => 'Design system mobile (composants UI)',
                'description'  => 'Bibliothèque de composants réutilisables : boutons, cartes, formulaires, navigation. Guide de style Figma.',
                'status'       => 'done',
                'priority'     => 'medium',
                'assigned_to'  => $sara->id,
                'due_date'     => '2026-04-24',
                'position'     => 2,
                'ai_generated' => false,
            ],
        ];

        foreach ($tasks_p3 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $p3->id,
                'created_by' => $admin->id,
            ]));
        }

        $this->command->info('✅ Projet 3 créé — HRConnect (7 tâches)');

        // ══════════════════════════════════════════════════════
        // 5. PROJET 4 — Microservices (TERMINÉ — pour montrer
        //    le statut "completed" dans le dashboard)
        // ══════════════════════════════════════════════════════

        $p4 = Project::create([
            'owner_id'    => $admin2->id,
            'name'        => 'MicroCore — Architecture Microservices',
            'description' => 'Migration de l\'application monolithique vers microservices : Docker, Kubernetes GKE, API Gateway Kong, monitoring Grafana/Prometheus.',
            'status'      => 'completed',
        ]);

        $p4->members()->attach([
            $admin2->id  => ['role' => 'admin'],
            $admin->id   => ['role' => 'member'],
            $ines->id    => ['role' => 'member'],
            $nadia->id   => ['role' => 'member'],
        ]);

        $tasks_p4 = [
            ['title' => 'Analyse et découpage du monolithe en services',    'priority' => 'high',   'assigned_to' => $admin2->id, 'due_date' => '2026-03-10', 'position' => 0],
            ['title' => 'Service d\'authentification JWT indépendant',       'priority' => 'high',   'assigned_to' => $admin->id,  'due_date' => '2026-03-20', 'position' => 1],
            ['title' => 'API Gateway Kong — routing et rate limiting',       'priority' => 'high',   'assigned_to' => $admin2->id, 'due_date' => '2026-03-28', 'position' => 2],
            ['title' => 'Containerisation Docker de tous les services',      'priority' => 'medium', 'assigned_to' => $ines->id,   'due_date' => '2026-04-05', 'position' => 3],
            ['title' => 'Déploiement Kubernetes sur GKE (prod)',             'priority' => 'high',   'assigned_to' => $ines->id,   'due_date' => '2026-04-12', 'position' => 4],
            ['title' => 'Pipeline CI/CD GitHub Actions complet',             'priority' => 'high',   'assigned_to' => $ines->id,   'due_date' => '2026-04-16', 'position' => 5],
            ['title' => 'Monitoring Grafana + Prometheus + alerting PD',     'priority' => 'medium', 'assigned_to' => $admin2->id, 'due_date' => '2026-04-20', 'position' => 6],
            ['title' => 'Documentation technique complète (Swagger + wiki)', 'priority' => 'low',    'assigned_to' => $nadia->id,  'due_date' => '2026-04-25', 'position' => 7],
        ];

        foreach ($tasks_p4 as $t) {
            Task::create([
                'project_id'   => $p4->id,
                'created_by'   => $admin2->id,
                'assigned_to'  => $t['assigned_to'],
                'title'        => $t['title'],
                'description'  => '',
                'status'       => 'done',
                'priority'     => $t['priority'],
                'position'     => $t['position'],
                'due_date'     => $t['due_date'],
                'ai_generated' => false,
            ]);
        }

        $this->command->info('✅ Projet 4 créé — MicroCore (8 tâches, terminé)');

        // ══════════════════════════════════════════════════════
        // 6. COMMENTAIRES — pour alimenter le Recent Activity
        //    et montrer la fonctionnalité de collaboration
        // ══════════════════════════════════════════════════════

        // Récupérer des tâches pour les commentaires
        $taskCI      = Task::where('title', 'LIKE', '%GitHub Actions CI/CD%')->where('project_id', $p1->id)->first();
        $taskKanban  = Task::where('title', 'LIKE', '%Kanban Board%')->first();
        $taskIA      = Task::where('title', 'LIKE', '%Génération de tâches par IA%')->first();
        $taskStripe  = Task::where('title', 'LIKE', '%Stripe Checkout%')->first();
        $taskAPI     = Task::where('title', 'LIKE', '%API REST fiches%')->first();
        $taskPJ      = Task::where('title', 'LIKE', '%pièces jointes%')->first();
        $taskConges  = Task::where('title', 'LIKE', '%gestion des congés%')->first();
        $taskRoles   = Task::where('title', 'LIKE', '%Système de rôles%')->first();

        // ── Commentaires récents pour le dashboard activity feed ──
        if ($taskIA) {
            Comment::create([
                'task_id'    => $taskIA->id,
                'user_id'    => $admin->id,
                'body'       => '🎉 La génération IA est opérationnelle ! Groq LLaMA 3 génère 5 tâches en moins de 3 secondes et les assigne automatiquement selon les compétences de chaque membre. Testez avec le bouton ✨ sur n\'importe quel projet !',
                'created_at' => now()->subMinutes(8),
                'updated_at' => now()->subMinutes(8),
            ]);
            Comment::create([
                'task_id'    => $taskIA->id,
                'user_id'    => $sara->id,
                'body'       => 'Impressionnant ! L\'IA a assigné les tâches frontend à moi et les tâches backend à Yassine automatiquement. C\'est exactement ce qu\'on voulait 👌',
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ]);
        }

        if ($taskKanban) {
            Comment::create([
                'task_id'    => $taskKanban->id,
                'user_id'    => $nadia->id,
                'body'       => 'Le drag & drop fonctionne parfaitement avec SortableJS. La position est bien sauvegardée en base via AJAX. J\'ai aussi ajouté les badges deadline (rouge si en retard, orange si dans 2 jours) ✅',
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ]);
        }

        if ($taskCI) {
            Comment::create([
                'task_id'    => $taskCI->id,
                'user_id'    => $ines->id,
                'body'       => 'J\'ai commencé le pipeline GitHub Actions. Les tests PHPUnit tournent bien sur la branche main. Je bloque sur la configuration du déploiement sur staging — quelqu\'un a les credentials serveur ?',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ]);
            Comment::create([
                'task_id'    => $taskCI->id,
                'user_id'    => $admin->id,
                'body'       => 'Je t\'ai ajouté les secrets GitHub (SSH_KEY, SERVER_HOST, DB_PASSWORD). Check les variables d\'environnement dans Settings > Secrets du repo. Pour le staging, utilise le workflow `deploy-staging.yml` que j\'ai créé.',
                'created_at' => now()->subMinutes(45),
                'updated_at' => now()->subMinutes(45),
            ]);
        }

        if ($taskStripe) {
            Comment::create([
                'task_id'    => $taskStripe->id,
                'user_id'    => $yassine->id,
                'body'       => '⚠️ Cette tâche est en retard ! J\'ai eu un problème avec les webhooks Stripe en production (timeout). J\'ai identifié la cause : le queue worker n\'était pas démarré sur le serveur. C\'est réglé, je reprends l\'intégration maintenant.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);
            Comment::create([
                'task_id'    => $taskStripe->id,
                'user_id'    => $admin2->id,
                'body'       => 'OK, pas de panique. Ajoute `STRIPE_WEBHOOK_SECRET` dans le .env de prod. Et pense à utiliser `stripe listen` en local pour tester. Nouvelle deadline : 8 mai.',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ]);
        }

        if ($taskAPI) {
            Comment::create([
                'task_id'    => $taskAPI->id,
                'user_id'    => $admin->id,
                'body'       => 'Endpoint GET /api/payslips/{month} opérationnel. Retourne le PDF encodé en base64. Collection Postman mise à jour dans le repo. Tests unitaires à 100% ✅',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ]);
            Comment::create([
                'task_id'    => $taskAPI->id,
                'user_id'    => $ines->id,
                'body'       => 'Intégré côté mobile avec react-native-pdf, ça marche super bien ! Par contre le temps de réponse est ~1.2s, on peut mettre en cache Redis ?',
                'created_at' => now()->subMinutes(90),
                'updated_at' => now()->subMinutes(90),
            ]);
        }

        if ($taskPJ) {
            Comment::create([
                'task_id'    => $taskPJ->id,
                'user_id'    => $nadia->id,
                'body'       => 'Upload fonctionnel pour PDF, images, Word, Excel. Drag & drop dans le modal. Les images ont un aperçu miniature. Limite 10MB/fichier. Il reste le téléchargement sécurisé à finaliser.',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ]);
        }

        if ($taskRoles) {
            Comment::create([
                'task_id'    => $taskRoles->id,
                'user_id'    => $admin->id,
                'body'       => 'Système de rôles complètement refactorisé : les admins peuvent inviter des membres par email avec un lien sécurisé (7 jours). Les membres voient uniquement leurs tâches dans "Mes Tâches". Page Team avec gestion des compétences par l\'admin ✅',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ]);
        }

        if ($taskConges) {
            Comment::create([
                'task_id'    => $taskConges->id,
                'user_id'    => $omar->id,
                'body'       => 'Réunion spec avec les RH ce matin. Points clés : validation en 2 étapes (manager > RH), jours fériés marocains automatiques (API publique disponible), solde en temps réel. Je mets à jour la doc de spécification.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]);
        }

        $this->command->info('✅ Commentaires créés');

        // ══════════════════════════════════════════════════════
        // RÉSUMÉ FINAL
        // ══════════════════════════════════════════════════════

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════╗');
        $this->command->info('║        DevCollab — Dataset de Démo           ║');
        $this->command->info('╠══════════════════════════════════════════════╣');
        $this->command->info('║ 👤 COMPTES                                   ║');
        $this->command->info('║   Admin 1 : admin@devcollab.io               ║');
        $this->command->info('║   Admin 2 : karim@devcollab.io               ║');
        $this->command->info('║   Membre  : sara@devcollab.io                ║');
        $this->command->info('║   Membre  : yassine@devcollab.io             ║');
        $this->command->info('║   Membre  : nadia@devcollab.io               ║');
        $this->command->info('║   Membre  : omar@devcollab.io                ║');
        $this->command->info('║   Membre  : ines@devcollab.io                ║');
        $this->command->info('║   MDP Admin   : Admin@2024                   ║');
        $this->command->info('║   MDP Membres : Member@2024                  ║');
        $this->command->info('╠══════════════════════════════════════════════╣');
        $this->command->info('║ 📁 PROJETS                                   ║');
        $this->command->info('║   P1 : DevCollab       — 15 tâches (actif)  ║');
        $this->command->info('║   P2 : ShopFlow         — 9 tâches  (actif) ║');
        $this->command->info('║   P3 : HRConnect        — 7 tâches  (actif) ║');
        $this->command->info('║   P4 : MicroCore        — 8 tâches  (done)  ║');
        $this->command->info('╠══════════════════════════════════════════════╣');
        $this->command->info('║ 🎯 FONCTIONNALITÉS EN DÉMO                  ║');
        $this->command->info('║   ✅ Kanban + drag & drop                    ║');
        $this->command->info('║   ✅ IA génération + distribution            ║');
        $this->command->info('║   ✅ Deadlines (retard visible sur P2)       ║');
        $this->command->info('║   ✅ Badges priorité + IA                    ║');
        $this->command->info('║   ✅ Tâches avec compétences assignées       ║');
        $this->command->info('║   ✅ Commentaires récents dans Activity      ║');
        $this->command->info('║   ✅ Rôles Admin (2) vs Membres (5)          ║');
        $this->command->info('║   ✅ Projet terminé visible (MicroCore)      ║');
        $this->command->info('╚══════════════════════════════════════════════╝');
        $this->command->info('');
    }
}