@extends('admin.layouts.master')

@section('title', 'Facture #' . $invoice->invoice_number)

@section('head')
    <style>
        .invoice-preview {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #4e73df;
            padding: 1rem;
            border-radius: 4px;
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
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary mb-2">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                    <h4 class="page-title mb-0">Facture #{{ $invoice->invoice_number }}</h4>
                </div>
                <div>
                    @if(!$invoice->pdf_path)
                        <form action="{{ route('admin.invoices.generate-pdf', $invoice) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-file-pdf-box"></i> Générer PDF
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.invoices.generate-pdf', $invoice) }}" class="btn btn-success">
                            <i class="mdi mdi-download"></i> Voir PDF
                        </a>
                    @endif
                    @if($invoice->status != 'paid')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#markPaidModal">
                            <i class="mdi mdi-check-circle"></i> Marquer Payée
                        </button>
                    @endif
                    @if($invoice->status != 'paid' && $invoice->status != 'cancelled')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelInvoiceModal">
                            <i class="mdi mdi-close-circle"></i> Annuler
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card invoice-preview mb-3">
                <div class="invoice-header">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="text-white mb-3">FACTURE</h2>
                            <p class="mb-1"><strong>N° {{ $invoice->invoice_number }}</strong></p>
                            <p class="mb-1">Date: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                            <p class="mb-0">Échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
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
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6 class="mb-3"><i class="mdi mdi-account-circle me-2"></i>Facturé à:</h6>
                                @if($invoice->order->user_id && $invoice->order->user)
                                    <p class="mb-1"><strong>{{ $invoice->order->user->name }}</strong></p>
                                    <p class="mb-1">{{ $invoice->order->user->address_line1 }}</p>
                                    @if($invoice->order->user->address_line2)
                                        <p class="mb-1">{{ $invoice->order->user->address_line2 }}</p>
                                    @endif
                                    @if($invoice->order->user->postal_code && $invoice->order->user->city)
                                        <p class="mb-1">{{ $invoice->order->user->postal_code }} {{ $invoice->order->user->city }}</p>
                                    @endif
                                    <p class="mb-1"><i class="mdi mdi-phone"></i> {{ $invoice->order->user->phone }}</p>
                                    <p class="mb-0"><i class="mdi mdi-email"></i> {{ $invoice->order->user->email }}</p>
                                @elseif($invoice->order->guest_name)
                                    <p class="mb-1"><strong>{{ $invoice->order->guest_name }}</strong> <span class="badge bg-info">Invité</span></p>
                                    <p class="mb-1">{{ $invoice->order->guest_address_line1 }}</p>
                                    @if($invoice->order->guest_address_line2)
                                        <p class="mb-1">{{ $invoice->order->guest_address_line2 }}</p>
                                    @endif
                                    @if($invoice->order->guest_postal_code && $invoice->order->guest_city)
                                        <p class="mb-1">{{ $invoice->order->guest_postal_code }} {{ $invoice->order->guest_city }}</p>
                                    @endif
                                    <p class="mb-1"><i class="mdi mdi-phone"></i> {{ $invoice->order->guest_phone }}</p>
                                    <p class="mb-0"><i class="mdi mdi-email"></i> {{ $invoice->order->guest_email }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6 class="mb-3"><i class="mdi mdi-cart me-2"></i>Commande:</h6>
                                <p class="mb-1"><strong>N° {{ $invoice->order->order_number }}</strong></p>
                                <p class="mb-0">Date: {{ $invoice->order->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix Unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong><br>
                                            <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                            @if($item->variant_details)
                                                <br>
                                                <small class="text-muted">
                                                    @foreach($item->variant_details['attributes'] ?? [] as $key => $value)
                                                        <span class="badge bg-light text-dark">{{ $key }}: {{ $value }}</span>
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center"><span class="badge bg-primary">{{ $item->quantity }}</span></td>
                                        <td class="text-end">{{ number_format($item->price, 2) }} DH</td>
                                        <td class="text-end"><strong>{{ number_format($item->total, 2) }} DH</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($invoice->subtotal, 2) }} DH</strong></td>
                                </tr>
                                @if($invoice->discount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            <strong>Réduction:</strong>
                                            @if($invoice->order->promotion)
                                                <br><small class="text-muted"><i class="mdi mdi-tag"></i> {{ $invoice->order->promotion->name }}</small>
                                            @endif
                                            @if($invoice->order->coupon)
                                                <br><small class="text-muted"><i class="mdi mdi-ticket-percent"></i> {{ $invoice->order->coupon->code }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong class="text-danger">-{{ number_format($invoice->discount, 2) }} DH</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Frais de livraison:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($invoice->shipping_cost, 2) }} DH</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end"><strong class="fs-5">TOTAL TTC:</strong></td>
                                    <td class="text-end"><strong class="fs-5 text-primary">{{ number_format($invoice->total, 2) }} DH</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                        <div class="alert alert-info">
                            <h6><i class="mdi mdi-note-text"></i> Notes:</h6>
                            <p class="mb-0">{{ $invoice->notes }}</p>
                        </div>
                    @endif

                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="text-muted mb-0">Merci pour votre commande!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    @php
                        $statusColors = [
                            'draft' => 'secondary',
                            'sent' => 'warning',
                            'paid' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $statusLabels = [
                            'draft' => 'Brouillon',
                            'sent' => 'Envoyée',
                            'paid' => 'Payée',
                            'cancelled' => 'Annulée'
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$invoice->status] ?? 'secondary' }} status-badge-xl">
                            {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                        </span>
                    </div>
                    <h3 class="text-primary mb-0">{{ number_format($invoice->total, 2) }} DH</h3>
                    <p class="text-muted">Montant Total</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="mdi mdi-information me-2"></i>Informations Facture</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Date facture:</small>
                        <p class="mb-0 fw-bold">{{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Date échéance:</small>
                        <p class="mb-0 fw-bold">{{ $invoice->due_date->format('d/m/Y') }}</p>
                    </div>
                    @if($invoice->payment_date)
                        <div class="mb-3">
                            <small class="text-muted">Date paiement:</small>
                            <p class="mb-0 fw-bold text-success">{{ $invoice->payment_date->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>


            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="mdi mdi-cart me-2"></i>Commande Associée</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">N° Commande:</small>
                        <p class="mb-0 fw-bold">{{ $invoice->order->order_number }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Client:</small>
                        <p class="mb-0">{{ $invoice->order->user_id && $invoice->order->user ? $invoice->order->user->name : ($invoice->order->guest_name ?? 'Invité') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Date:</small>
                        <p class="mb-0">{{ $invoice->order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.orders.show', $invoice->order) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="mdi mdi-eye"></i> Voir Commande
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white">Marquer comme Payée</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Confirmez-vous que cette facture a été payée ?</p>
                    <div class="mb-3">
                        <label class="form-label">Date de paiement</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Oui, marquer comme payée</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelInvoiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.invoices.cancel', $invoice) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white">Annuler la Facture</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir annuler cette facture ?</p>
                    <p class="text-muted mb-0"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder</button>
                    <button type="submit" class="btn btn-danger">Oui, annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateInvoiceStatus() {
    const status = document.getElementById('invoiceStatus').value;
    
    fetch('{{ route("admin.invoices.update-status", $invoice) }}', {
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
