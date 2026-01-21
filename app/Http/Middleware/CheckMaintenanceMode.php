<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteSetting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si le mode maintenance est activé
        $maintenanceMode = SiteSetting::get('maintenance_mode', '0');
        
        // Exclure les routes admin, la route de maintenance et la newsletter
        if ($maintenanceMode == '1' && 
            !$request->is('admin/*') && 
            !$request->is('maintenance') && 
            !$request->is('maintenance/verify') &&
            !$request->is('newsletter/*') &&
            !session('maintenance_access')) {
            return redirect()->route('maintenance.index');
        }
        
        return $next($request);
    }
}
