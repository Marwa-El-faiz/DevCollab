<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    // ── Générer des tâches — ADMIN ONLY ──
    public function generate(Request $request, Project $project)
    {
        // Vérifier accès admin
        if (!$project->isAdmin(Auth::id())) {
            return redirect()->back()
                ->with('error', 'Seul un administrateur peut générer des tâches avec l\'IA.');
        }

        // Récupérer les membres avec leurs compétences
        $members = $project->members()->get();

        // Construire la liste des membres avec compétences pour le prompt
        $membersInfo = $members->map(fn($m) => [
            'id'        => $m->id,
            'name'      => $m->name,
            'skills'    => $m->skills ?? 'Compétences générales',
            'job_title' => $m->job_title ?? 'Membre',
        ]);

        $membersText = $membersInfo->map(fn($m) =>
            "- {$m['name']} (ID:{$m['id']}) — {$m['job_title']} — Compétences: {$m['skills']}"
        )->implode("\n");

        $prompt = "Tu es un chef de projet expert en gestion d'équipe.
Génère exactement 5 tâches concrètes pour ce projet ET assigne chaque tâche au membre le plus adapté selon ses compétences.

Nom du projet : {$project->name}
Description : {$project->description}

Membres disponibles :
{$membersText}

Règles d'assignation :
- Assigne chaque tâche au membre dont les compétences correspondent le mieux
- Équilibre la charge entre les membres
- Utilise EXACTEMENT les IDs fournis pour l'assignation

Réponds UNIQUEMENT avec un tableau JSON valide, sans texte avant ou après.
Format exact :
[
  {
    \"title\": \"Titre de la tâche\",
    \"description\": \"Description courte et concrète\",
    \"priority\": \"high\",
    \"assigned_to\": 1,
    \"reason\": \"Assigné à [nom] car [compétence correspondante]\"
  }
]
Les priorités possibles sont : low, medium, high.
Le champ assigned_to doit être l'ID numérique exact d'un des membres.
Génère exactement 5 tâches.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
                'messages'    => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.6,
                'max_tokens'  => 1500,
            ]);

            if (!$response->successful()) {
                return back()->with('error', 'Erreur API : ' . $response->body());
            }

            $content = $response->json('choices.0.message.content');
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            $content = trim($content);

            $tasks = json_decode($content, true);

            if (!$tasks || !is_array($tasks)) {
                return back()->with('error', 'L\'IA n\'a pas retourné un format valide. Réessaie.');
            }

            $position   = $project->tasks()->count();
            $memberIds  = $members->pluck('id')->toArray();
            $created    = 0;

            foreach ($tasks as $taskData) {
                // Valider que l'assigned_to est un membre du projet
                $assignedTo = isset($taskData['assigned_to'])
                    && in_array((int)$taskData['assigned_to'], $memberIds)
                    ? (int)$taskData['assigned_to']
                    : null;

                Task::create([
                    'project_id'   => $project->id,
                    'created_by'   => Auth::id(),
                    'assigned_to'  => $assignedTo,
                    'title'        => $taskData['title'],
                    'description'  => ($taskData['description'] ?? '') .
                                     (isset($taskData['reason']) ? "\n\n💡 " . $taskData['reason'] : ''),
                    'status'       => 'todo',
                    'priority'     => $taskData['priority'] ?? 'medium',
                    'position'     => $position++,
                    'ai_generated' => true,
                ]);
                $created++;
            }

            return back()->with('success',
                "✨ {$created} tâches générées et distribuées automatiquement par l'IA !");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // ── Distribution IA des tâches existantes — ADMIN ONLY ──
    public function distribute(Request $request, Project $project)
    {
        if (!$project->isAdmin(Auth::id())) {
            return redirect()->back()
                ->with('error', 'Seul un administrateur peut distribuer les tâches.');
        }

        // Tâches non assignées
        $unassignedTasks = $project->tasks()
            ->whereNull('assigned_to')
            ->get();

        if ($unassignedTasks->isEmpty()) {
            return back()->with('error', 'Aucune tâche non assignée à distribuer.');
        }

        $members = $project->members()->get();

        if ($members->isEmpty()) {
            return back()->with('error', 'Aucun membre dans ce projet.');
        }

        $tasksText = $unassignedTasks->map(fn($t) =>
            "- ID:{$t->id} | {$t->title} | Priorité: {$t->priority} | {$t->description}"
        )->implode("\n");

        $membersText = $members->map(fn($m) =>
            "- ID:{$m->id} | {$m->name} | {$m->job_title} | Compétences: {$m->skills}"
        )->implode("\n");

        $prompt = "Tu es un chef de projet. Distribue ces tâches non assignées aux membres selon leurs compétences.

Tâches à distribuer :
{$tasksText}

Membres disponibles :
{$membersText}

Réponds UNIQUEMENT avec un tableau JSON :
[{\"task_id\": 1, \"assigned_to\": 2, \"reason\": \"Raison courte\"}]";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
                'messages'    => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.5,
                'max_tokens'  => 800,
            ]);

            $content = $response->json('choices.0.message.content');
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            $content = trim($content);

            $assignments = json_decode($content, true);

            if (!$assignments || !is_array($assignments)) {
                return back()->with('error', 'L\'IA n\'a pas pu distribuer les tâches.');
            }

            $memberIds = $members->pluck('id')->toArray();
            $count     = 0;

            foreach ($assignments as $assignment) {
                $task = $unassignedTasks->find($assignment['task_id'] ?? null);
                $assignedTo = (int)($assignment['assigned_to'] ?? 0);

                if ($task && in_array($assignedTo, $memberIds)) {
                    $task->update(['assigned_to' => $assignedTo]);
                    $count++;
                }
            }

            return back()->with('success',
                "✨ {$count} tâches distribuées automatiquement par l'IA !");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}