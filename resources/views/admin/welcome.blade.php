@extends('admin.layouts.master')

@section('title', 'Bienvenue - Administration')

@section('content')
    <div class="row justify-content-center align-items-center mt-3" style="min-height: 70vh;">
        <div class="col-md-12 text-center">
            <div class="card shadow-lg border-0">
                <div class="card-body p-2">
                    <div class="mb-4">
                        <i class="fas fa-shield-alt" style="font-size: 80px; color: #4e73df;"></i>
                    </div>
                    <h1 class="display-4 mb-1" style="font-weight: bold; color: #2e3338;">
                        Bienvenue dans l'Administration
                    </h1>
                    <p class="lead text-muted mb-1">
                        Bonjour <strong>{{ Auth::guard('admin')->user()->name }}</strong>,<br>
                        Vous êtes connecté en tant qu'administrateur.
                    </p>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <h6 class="font-weight-bold">Utilisateurs</h6>
                                    <p class="small text-muted mb-0">Gérer les comptes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-1">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-box fa-2x text-success mb-2"></i>
                                    <h6 class="font-weight-bold">Produits</h6>
                                    <p class="small text-muted mb-0">Catalogue complet</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-1">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                                    <h6 class="font-weight-bold">Commandes</h6>
                                    <p class="small text-muted mb-0">Suivi des ventes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

</script>
@endsection
