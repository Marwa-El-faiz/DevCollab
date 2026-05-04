<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function generate(Request $request, Project $project)
    {
       
        $userId = Auth::id();
        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();

        if (!$hasAccess) {
            abort(403);
        }

        $prompt = "Tu es un chef de projet expert. 
Génère exactement 5 tâches concrètes pour ce projet :

Nom du projet : {$project->name}
Description : {$project->description}

Réponds UNIQUEMENT avec un tableau JSON valide, sans texte avant ou après.
Format exact :
[
  {\"title\": \"Titre de la tâche\", \"description\": \"Description courte\", \"priority\": \"high\"},
  {\"title\": \"Titre\", \"description\": \"Description\", \"priority\": \"medium\"},
  {\"title\": \"Titre\", \"description\": \"Description\", \"priority\": \"low\"}
]
Les priorités possibles sont : low, medium, high.
Génère exactement 5 tâches.";

        try {
            // Appel API Groq
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),                'messages'    => [
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens'  => 1000,
            ]);

            if (!$response->successful()) {
                return back()->with('error', 'Erreur API : ' . $response->body());
            }

            // Extraire le JSON de la réponse
            $content = $response->json('choices.0.message.content');

            // Nettoyer la réponse (enlever les ```json si présents)
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            $content = trim($content);

            $tasks = json_decode($content, true);

            if (!$tasks || !is_array($tasks)) {
                return back()->with('error', 'L\'IA n\'a pas retourné un format valide. Réessaie.');
            }

            // Sauvegarder les tâches générées
            $position = $project->tasks()->count();

            foreach ($tasks as $taskData) {
                Task::create([
                    'project_id'   => $project->id,
                    'created_by'   => Auth::id(),
                    'assigned_to'  => null,
                    'title'        => $taskData['title'],
                    'description'  => $taskData['description'] ?? '',
                    'status'       => 'todo',
                    'priority'     => $taskData['priority'] ?? 'medium',
                    'position'     => $position++,
                    'ai_generated' => true,
                ]);
            }

            $count = count($tasks);
            return back()->with('success', "✨ {$count} tâches générées par l'IA avec succès !");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}