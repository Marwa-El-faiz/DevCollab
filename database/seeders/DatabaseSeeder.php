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


        $admin = User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        $sarah = User::create([
            'name'     => 'Sarah K.',
            'email'    => 'sarah@devcollab.app',
            'password' => Hash::make('password123'),
            'role'     => 'member',
        ]);

        $mike = User::create([
            'name'     => 'Amine K.',
            'email'    => 'mike@devcollab.app',
            'password' => Hash::make('password123'),
            'role'     => 'member',
        ]);

        $anna = User::create([
            'name'     => 'Annas M.',
            'email'    => 'anna@devcollab.app',
            'password' => Hash::make('password123'),
            'role'     => 'member',
        ]);

        $luke = User::create([
            'name'     => 'Ahmed H.',
            'email'    => 'luke@devcollab.app',
            'password' => Hash::make('password123'),
            'role'     => 'member',
        ]);

       
        $project1 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'Mobile App Redesign',
            'description' => 'Complete UI overhaul for iOS and Android apps',
            'status'      => 'active',
        ]);

        $project2 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'API v2 Migration',
            'description' => 'Migrate all endpoints to new REST architecture',
            'status'      => 'active',
        ]);

        $project3 = Project::create([
            'owner_id'    => $admin->id,
            'name'        => 'Dashboard Analytics',
            'description' => 'Build real-time analytics dashboard',
            'status'      => 'active',
        ]);

      
        $project1->members()->attach([
            $admin->id => ['role' => 'admin'],
            $sarah->id => ['role' => 'member'],
            $anna->id  => ['role' => 'member'],
        ]);

        $project2->members()->attach([
            $admin->id => ['role' => 'admin'],
            $mike->id  => ['role' => 'member'],
            $sarah->id => ['role' => 'member'],
        ]);

        $project3->members()->attach([
            $admin->id => ['role' => 'admin'],
            $anna->id  => ['role' => 'member'],
            $sarah->id => ['role' => 'member'],
            $luke->id  => ['role' => 'member'],
        ]);

        

        $tasks1 = [
            // TO DO
            ['title' => 'Design settings page mockup',     'status' => 'todo',        'priority' => 'medium', 'assigned_to' => $anna->id,  'due_date' => '2026-04-25', 'position' => 0],
            ['title' => 'Write API documentation',         'status' => 'todo',        'priority' => 'low',    'assigned_to' => $mike->id,  'due_date' => '2026-04-28', 'position' => 1],
            ['title' => 'Implement dark mode toggle',      'status' => 'todo',        'priority' => 'medium', 'assigned_to' => $admin->id, 'due_date' => '2026-04-24', 'position' => 2],
            // IN PROGRESS
            ['title' => 'Update user authentication flow', 'status' => 'in_progress', 'priority' => 'high',   'assigned_to' => $admin->id, 'due_date' => '2026-04-22', 'position' => 0],
            ['title' => 'Fix mobile navigation bug',       'status' => 'in_progress', 'priority' => 'high',   'assigned_to' => $sarah->id, 'due_date' => '2026-04-21', 'position' => 1],
            // DONE
            ['title' => 'Setup CI/CD pipeline',            'status' => 'done',        'priority' => 'high',   'assigned_to' => $luke->id,  'due_date' => '2026-04-23', 'position' => 0],
            ['title' => 'Database migration script',       'status' => 'done',        'priority' => 'medium', 'assigned_to' => $mike->id,  'due_date' => '2026-04-20', 'position' => 1],
            ['title' => 'Design system tokens',            'status' => 'done',        'priority' => 'medium', 'assigned_to' => $sarah->id, 'due_date' => '2026-04-18', 'position' => 2],
            ['title' => 'Mobile nav component',            'status' => 'done',        'priority' => 'low',    'assigned_to' => $admin->id, 'due_date' => '2026-04-17', 'position' => 3],
        ];

        foreach ($tasks1 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $project1->id,
                'created_by' => $admin->id,
            ]));
        }

        $tasks2 = [
            ['title' => 'Map all legacy endpoints',        'status' => 'todo',        'priority' => 'high',   'assigned_to' => $mike->id,  'due_date' => '2026-04-30', 'position' => 0],
            ['title' => 'Write migration plan document',   'status' => 'todo',        'priority' => 'medium', 'assigned_to' => $sarah->id, 'due_date' => '2026-05-02', 'position' => 1],
            ['title' => 'Setup new API routes',            'status' => 'in_progress', 'priority' => 'high',   'assigned_to' => $admin->id, 'due_date' => '2026-04-28', 'position' => 0],
            ['title' => 'API authentication flow',         'status' => 'in_progress', 'priority' => 'high',   'assigned_to' => $mike->id,  'due_date' => '2026-04-27', 'position' => 1],
            ['title' => 'Deprecate v1 endpoints',          'status' => 'done',        'priority' => 'medium', 'assigned_to' => $sarah->id, 'due_date' => '2026-04-20', 'position' => 0],
            ['title' => 'Update API documentation',        'status' => 'done',        'priority' => 'low',    'assigned_to' => $admin->id, 'due_date' => '2026-04-19', 'position' => 1],
            ['title' => 'Create Postman collection',       'status' => 'done',        'priority' => 'low',    'assigned_to' => $mike->id,  'due_date' => '2026-04-15', 'position' => 2],
        ];

        foreach ($tasks2 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $project2->id,
                'created_by' => $admin->id,
            ]));
        }

      

        $tasks3 = [
            ['title' => 'Chart library integration',       'status' => 'todo',        'priority' => 'high',   'assigned_to' => $luke->id,  'due_date' => '2026-04-30', 'position' => 0],
            ['title' => 'Real-time data connection',       'status' => 'in_progress', 'priority' => 'high',   'assigned_to' => $anna->id,  'due_date' => '2026-04-29', 'position' => 0],
            ['title' => 'User testing plan',               'status' => 'in_progress', 'priority' => 'medium', 'assigned_to' => $sarah->id, 'due_date' => '2026-05-01', 'position' => 1],
            ['title' => 'Design dashboard wireframes',     'status' => 'done',        'priority' => 'high',   'assigned_to' => $sarah->id, 'due_date' => '2026-04-15', 'position' => 0],
            ['title' => 'Setup analytics database',        'status' => 'done',        'priority' => 'medium', 'assigned_to' => $luke->id,  'due_date' => '2026-04-16', 'position' => 1],
            ['title' => 'Create KPI report template',      'status' => 'done',        'priority' => 'medium', 'assigned_to' => $anna->id,  'due_date' => '2026-04-17', 'position' => 2],
            ['title' => 'Performance benchmarking',        'status' => 'done',        'priority' => 'low',    'assigned_to' => $admin->id, 'due_date' => '2026-04-18', 'position' => 3],
            ['title' => 'Export to PDF feature',           'status' => 'done',        'priority' => 'medium', 'assigned_to' => $luke->id,  'due_date' => '2026-04-19', 'position' => 4],
            ['title' => 'Responsive layout fixes',         'status' => 'done',        'priority' => 'low',    'assigned_to' => $sarah->id, 'due_date' => '2026-04-20', 'position' => 5],
            ['title' => 'Dark mode for charts',            'status' => 'done',        'priority' => 'low',    'assigned_to' => $anna->id,  'due_date' => '2026-04-21', 'position' => 6],
            ['title' => 'Filter by date range',            'status' => 'done',        'priority' => 'medium', 'assigned_to' => $admin->id, 'due_date' => '2026-04-22', 'position' => 7],
        ];

        foreach ($tasks3 as $t) {
            Task::create(array_merge($t, [
                'project_id' => $project3->id,
                'created_by' => $admin->id,
            ]));
        }

       

        $taskAuth   = Task::where('title', 'API authentication flow')->first();
        $taskNav    = Task::where('title', 'Fix mobile navigation bug')->first();
        $taskDesign = Task::where('title', 'Design system tokens')->first();
        $taskChart  = Task::where('title', 'Chart library integration')->first();

        if ($taskDesign) {
            Comment::create([
                'task_id'    => $taskDesign->id,
                'user_id'    => $sarah->id,
                'body'       => 'Tokens finalisés et exportés sur Figma. Tâche complète ✓',
                'created_at' => now()->subMinutes(12),
                'updated_at' => now()->subMinutes(12),
            ]);
        }

        if ($taskAuth) {
            Comment::create([
                'task_id'    => $taskAuth->id,
                'user_id'    => $mike->id,
                'body'       => 'J\'ai terminé la partie OAuth2. Il reste à tester le refresh token.',
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ]);
        }

        if ($taskNav) {
            Comment::create([
                'task_id'    => $taskNav->id,
                'user_id'    => $admin->id,
                'body'       => 'J\'ai mis à jour le composant Mobile nav. Peux-tu retester ?',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);
            Comment::create([
                'task_id'    => $taskNav->id,
                'user_id'    => $sarah->id,
                'body'       => 'Le bug vient du composant NavBar sur iOS 16. Je suis dessus.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);
        }

        if ($taskChart) {
            Comment::create([
                'task_id'    => $taskChart->id,
                'user_id'    => $luke->id,
                'body'       => 'J\'ai assigné la tâche Chart library integration à moi-même.',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ]);
        }

        $this->command->info('');
        $this->command->info('Base de données remplie avec succès !');
        $this->command->info('');
        $this->command->info('Admin    : admin@test.com     / password123');
        $this->command->info('Sarah    : sarah@devcollab.app / password123');
        $this->command->info('Amine    : mike@devcollab.app  / password123');
        $this->command->info('Annas    : anna@devcollab.app  / password123');
        $this->command->info('Ahmed    : luke@devcollab.app  / password123');
        $this->command->info('');
        $this->command->info('3 projets | 27 tâches | 5 commentaires');
    }
}