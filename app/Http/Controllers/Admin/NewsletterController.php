<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = Newsletter::query();

        // Recherche
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $newsletters = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Newsletter::count(),
            'active' => Newsletter::where('is_active', true)->count(),
            'inactive' => Newsletter::where('is_active', false)->count(),
        ];

        return view('admin.newsletters.index', compact('newsletters', 'stats'));
    }

    public function toggleStatus($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->is_active = !$newsletter->is_active;
        $newsletter->save();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Statut mis à jour avec succès');
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Email supprimé avec succès');
    }

    public function export()
    {
        $newsletters = Newsletter::where('is_active', true)->get();
        
        $filename = 'newsletters_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($newsletters) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Date d\'inscription', 'Statut']);
            
            foreach ($newsletters as $newsletter) {
                fputcsv($file, [
                    $newsletter->email,
                    $newsletter->created_at->format('d/m/Y H:i'),
                    $newsletter->is_active ? 'Actif' : 'Inactif'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
