<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->whereHas('roles', function($q) {
            $q->where('name', 'customer');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $customers = $query->latest()->paginate(20);
        
        $stats = [
            'total' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->count(),
            'active' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->where('is_active', 1)->count(),
            'inactive' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->where('is_active', 0)->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders.items', 'addresses']);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Client supprimé avec succès.');
    }

    public function toggleStatus(User $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        
        $status = $customer->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Client {$status} avec succès.");
    }
}
