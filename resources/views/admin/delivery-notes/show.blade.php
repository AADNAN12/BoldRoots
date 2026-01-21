@extends('admin.layouts.master')

@section('title', 'Bon de Livraison #' . $deliveryNote->delivery_note_number)

@section('head')
    <style>
        .delivery-preview {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
        }
        .delivery-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px 8px 0 0;
        }
        .status-badge-xl {
            font-size: 1.1rem;
            padding: 0.6rem 1.2rem;
        }
        .info-box {
            background: #f8f9fc;
            border-left: 4px solid #1cc88a;
            padding: 1rem;
            border-radius: 4px;
        }
        .tracking-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .signature-box {
            border: 2px dashed #e3e6f0;
            height: 120px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fc;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <a href="{{ route('admin.delivery-notes.index') }}" class="btn btn-outline-secondary mb-2">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                    <h4 class="page-title mb-0">Bon de Livraison #{{ $deliveryNote->delivery_note_number }}</h4>
                </div>
                <div>
                    @if(!$deliveryNote->pdf_path)
                        <form action="{{ route('admin.delivery-notes.generate-pdf', $deliveryNote) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-file-pdf-box"></i> Générer PDF
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.delivery-notes.download-pdf', $deliveryNote) }}" class="btn btn-success">
                            <i class="mdi mdi-download"></i> Télécharger PDF
                        </a>
                    @endif
                    @if($deliveryNote->status != 'delivered')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#deliveredModal">
                            <i class="mdi mdi-check-circle"></i> Marquer Livrée
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card delivery-preview mb-3">
                <div class="delivery-header">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="text-white mb-3"><i class="mdi mdi-truck-delivery me-2"></i>BON DE LIVRAISON</h2>
                            <p class="mb-1"><strong>N° {{ $deliveryNote->delivery_note_number }}</strong></p>
                            <p class="mb-0">Date: {{ $deliveryNote->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <h4 class="text-white">Votre Entreprise</h4>
                            <p class="mb-0">Adresse de l'entreprise</p>
                            <p class="mb-0">Ville, Code Postal</p>
                            <p class="mb-0">Tél: +212 XXX XXX XXX</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if($deliveryNote->tracking_number)
                        <div class="tracking-box mb-4">
                            <h5 class="text-white mb-2"><i class="mdi mdi-package-variant me-2"></i>Numéro de Suivi</h5>
                            <h3 class="text-white mb-0"><code class="text-white">{{ $deliveryNote->tracking_number }}</code></h3>
                            @if($deliveryNote->carrier_name)
                                <p class="text-white mb-0 mt-2">Transporteur: {{ $deliveryNote->carrier_name }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6 class="mb-3"><i class="mdi mdi-map-marker me-2"></i>Livraison à:</h6>
                                @if($deliveryNote->order->user_id && $deliveryNote->order->user)
                                    <p class="mb-1"><strong>{{ $deliveryNote->order->user->name }}</strong></p>
                                    <p class="mb-1">{{ $deliveryNote->order->user->address_line1 }}</p>
                                    @if($deliveryNote->order->user->address_line2)
                                        <p class="mb-1">{{ $deliveryNote->order->user->address_line2 }}</p>
                                    @endif
                                    @if($deliveryNote->order->user->postal_code && $deliveryNote->order->user->city)
                                        <p class="mb-1">{{ $deliveryNote->order->user->postal_code }} {{ $deliveryNote->order->user->city }}</p>
                                    @endif
                                    <p class="mb-1"><i class="mdi mdi-phone"></i> {{ $deliveryNote->order->user->phone }}</p>
                                    <p class="mb-0"><i class="mdi mdi-email"></i> {{ $deliveryNote->order->user->email }}</p>
                                @elseif($deliveryNote->order->guest_name)
                                    <p class="mb-1"><strong>{{ $deliveryNote->order->guest_name }}</strong> <span class="badge bg-info">Invité</span></p>
                                    <p class="mb-1">{{ $deliveryNote->order->guest_address_line1 }}</p>
                                    @if($deliveryNote->order->guest_address_line2)
                                        <p class="mb-1">{{ $deliveryNote->order->guest_address_line2 }}</p>
                                    @endif
                                    @if($deliveryNote->order->guest_postal_code && $deliveryNote->order->guest_city)
                                        <p class="mb-1">{{ $deliveryNote->order->guest_postal_code }} {{ $deliveryNote->order->guest_city }}</p>
                                    @endif
                                    <p class="mb-1"><i class="mdi mdi-phone"></i> {{ $deliveryNote->order->guest_phone }}</p>
                                    <p class="mb-0"><i class="mdi mdi-email"></i> {{ $deliveryNote->order->guest_email }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6 class="mb-3"><i class="mdi mdi-information me-2"></i>Informations:</h6>
                                <p class="mb-1"><strong>Commande:</strong> {{ $deliveryNote->order->order_number }}</p>
                                <p class="mb-1"><strong>Date commande:</strong> {{ $deliveryNote->order->created_at->format('d/m/Y') }}</p>
                                @if($deliveryNote->carrier_name)
                                    <p class="mb-1"><strong>Transporteur:</strong> {{ $deliveryNote->carrier_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th>SKU</th>
                                    <th class="text-center">Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveryNote->order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->variant_details)
                                                <br>
                                                <small class="text-muted">
                                                    @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                                        <span class="badge bg-light text-dark">{{ $key }}: {{ $value }}</span>
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td><code>{{ $item->product_sku }}</code></td>
                                        <td class="text-center"><span class="badge bg-primary">{{ $item->quantity }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total Articles:</strong></td>
                                    <td class="text-center"><strong class="text-primary">{{ $deliveryNote->order->items->sum('quantity') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($deliveryNote->notes)
                        <div class="alert alert-info">
                            <h6><i class="mdi mdi-note-text"></i> Notes:</h6>
                            <p class="mb-0">{{ $deliveryNote->notes }}</p>
                        </div>
                    @endif

                    @if($deliveryNote->status == 'delivered' && $deliveryNote->delivered_at)
                        <div class="alert alert-success">
                            <h6><i class="mdi mdi-check-circle"></i> Livraison Confirmée</h6>
                            <p class="mb-1"><strong>Date:</strong> {{ $deliveryNote->delivered_at->format('d/m/Y H:i') }}</p>
                            @if($deliveryNote->recipient_name)
                                <p class="mb-0"><strong>Reçu par:</strong> {{ $deliveryNote->recipient_name }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-6">
                            <p class="mb-2 fw-bold">Signature du livreur:</p>
                            <div class="signature-box">
                                <span class="text-muted">Signature</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <p class="mb-2 fw-bold">Signature du destinataire:</p>
                            <div class="signature-box">
                                <span class="text-muted">Signature</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            'failed' => 'danger',
                            'returned' => 'secondary'
                        ];
                        $statusLabels = [
                            'pending' => 'En préparation',
                            'shipped' => 'En transit',
                            'delivered' => 'Livrée',
                            'failed' => 'Échec',
                            'returned' => 'Retournée'
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$deliveryNote->status] ?? 'secondary' }} status-badge-xl">
                            {{ $statusLabels[$deliveryNote->status] ?? $deliveryNote->status }}
                        </span>
                    </div>
                    <h4 class="text-muted mb-0">{{ $deliveryNote->order->items->sum('quantity') }} Articles</h4>
                    <p class="text-muted">Total à livrer</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="mdi mdi-information me-2"></i>Informations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Date création:</small>
                        <p class="mb-0 fw-bold">{{ $deliveryNote->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($deliveryNote->delivered_at)
                        <div class="mb-3">
                            <small class="text-muted">Date livraison:</small>
                            <p class="mb-0 fw-bold text-success">{{ $deliveryNote->delivered_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    @if($deliveryNote->carrier_name)
                        <div class="mb-3">
                            <small class="text-muted">Transporteur:</small>
                            <p class="mb-0 fw-bold">{{ $deliveryNote->carrier_name }}</p>
                        </div>
                    @endif
                    @if($deliveryNote->tracking_number)
                        <div class="mb-0">
                            <small class="text-muted">N° Suivi:</small>
                            <p class="mb-0"><code>{{ $deliveryNote->tracking_number }}</code></p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 text-white"><i class="mdi mdi-truck me-2"></i>Informations de Suivi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delivery-notes.update-tracking', $deliveryNote) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Transporteur</label>
                            <input type="text" name="carrier_name" class="form-control" value="{{ $deliveryNote->carrier_name }}" placeholder="Ex: DHL, Aramex..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Numéro de suivi</label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ $deliveryNote->tracking_number }}" placeholder="Ex: 1234567890" required>
                        </div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="mdi mdi-check"></i> Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 text-white"><i class="mdi mdi-swap-horizontal me-2"></i>Changer le Statut</h6>
                </div>
                <div class="card-body">
                    <form id="statusForm">
                        @csrf
                        <div class="mb-3">
                            <select name="status" class="form-select" id="deliveryStatus">
                                <option value="pending" {{ $deliveryNote->status == 'pending' ? 'selected' : '' }}>En préparation</option>
                                <option value="shipped" {{ $deliveryNote->status == 'shipped' ? 'selected' : '' }}>En transit</option>
                                <option value="delivered" {{ $deliveryNote->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="failed" {{ $deliveryNote->status == 'failed' ? 'selected' : '' }}>Échec</option>
                                <option value="returned" {{ $deliveryNote->status == 'returned' ? 'selected' : '' }}>Retournée</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="updateDeliveryStatus()">
                            <i class="mdi mdi-check"></i> Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="mdi mdi-cart me-2"></i>Commande Associée</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">N° Commande:</small>
                        <p class="mb-0 fw-bold">{{ $deliveryNote->order->order_number }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Client:</small>
                        <p class="mb-0">{{ $deliveryNote->order->user_id && $deliveryNote->order->user ? $deliveryNote->order->user->name : ($deliveryNote->order->guest_name ?? 'Invité') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Date:</small>
                        <p class="mb-0">{{ $deliveryNote->order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.orders.show', $deliveryNote->order) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="mdi mdi-eye"></i> Voir Commande
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deliveredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.delivery-notes.mark-delivered', $deliveryNote) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white">Confirmer la Livraison</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom du destinataire</label>
                        <input type="text" name="recipient_name" class="form-control" placeholder="Nom de la personne qui a reçu le colis">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Signature (optionnel)</label>
                        <textarea name="signature_image" class="form-control" rows="3" placeholder="Base64 de la signature ou commentaire"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer Livraison</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateDeliveryStatus() {
    const status = document.getElementById('deliveryStatus').value;
    
    fetch('{{ route("admin.delivery-notes.update-status", $deliveryNote) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de la mise à jour');
    });
}
</script>
@endsection
