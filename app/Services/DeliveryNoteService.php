<?php

namespace App\Services;

use App\Models\DeliveryNote;
use App\Models\Order;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Exception;

class DeliveryNoteService
{
    /**
     * Generate delivery note from order
     */
    public function generateDeliveryNote($orderId, $carrierName = null, $trackingNumber = null)
    {
        DB::beginTransaction();

        try {
            $order = Order::with(['user', 'items.product'])
                ->findOrFail($orderId);

            // Check if order is in valid status
            if (!in_array($order->status, ['processing', 'shipped'])) {
                throw new Exception("La commande doit être en cours de traitement ou expédiée");
            }

            // Check if delivery note already exists
            if ($order->delivery_note_generated) {
                $existingNote = DeliveryNote::where('order_id', $orderId)->first();
                if ($existingNote) {
                    return $existingNote;
                }
            }

            // Create delivery note
            $deliveryNote = DeliveryNote::create([
                'order_id' => $order->id,
                'delivery_number' => $this->generateDeliveryNumber(),
                'delivery_date' => now(),
                'carrier_name' => $carrierName,
                'tracking_number' => $trackingNumber,
                'status' => 'pending',
                'notes' => null,
            ]);

            // Mark order as delivery note generated
            $order->update(['delivery_note_generated' => true]);

            DB::commit();

            return $deliveryNote;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate unique delivery number
     */
    public function generateDeliveryNumber()
    {
        $prefix = 'DEL';
        $date = now()->format('Ymd');
        
        do {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $deliveryNumber = "{$prefix}-{$date}-{$random}";
        } while (DeliveryNote::where('delivery_number', $deliveryNumber)->exists());

        return $deliveryNumber;
    }

    /**
     * Generate PDF for delivery note
     */
    public function generateDeliveryNotePDF($deliveryNoteId)
    {
        try {
            set_time_limit(120);
            
            $deliveryNote = DeliveryNote::with([
                'order.user',
                'order.items.product'
            ])->findOrFail($deliveryNoteId);

            $companyInfo = CompanyInfo::first();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 60,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);

            // En-tête personnalisé
            $mpdf->SetHTMLHeader('
                <div style="width: 100%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 60%; vertical-align: top;">
                                <div>
                                    ' . ($companyInfo->logo ? '<img src="' . public_path('storage/' . $companyInfo->logo) . '" alt="Logo" width="200">' : '') . '
                                </div>
                            </td>
                            <td style="width: 40%; text-align: right; vertical-align: top;">
                                <div>
                                    <h3 style="margin: 0;">' . ($companyInfo->name ?? '') . '</h3>
                                    <p style="font-size: 12px; margin: 2px 0;">' . ($companyInfo->address ?? '') . '</p>
                                    <p style="font-size: 12px; margin: 2px 0;">' . ($companyInfo->city ?? '') . ', ' . ($companyInfo->postal_code ?? '') . '</p>
                                    ' . ($companyInfo->phone ? '<p style="font-size: 12px; margin: 2px 0;">Tél: ' . $companyInfo->phone . '</p>' : '') . '
                                    ' . ($companyInfo->email ? '<p style="font-size: 12px; margin: 2px 0;">Email: ' . $companyInfo->email . '</p>' : '') . '
                                    ' . ($companyInfo->tax_number ? '<p style="font-size: 12px; margin: 2px 0;">N° TVA: ' . $companyInfo->tax_number . '</p>' : '') . '
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            ');

            // Pied de page personnalisé
            $mpdf->SetHTMLFooter('
                <table style="width: 100%; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 100%; text-align: center;">
                            <p style="font-size: 12px;">Page {PAGENO} sur {nbpg}</p>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; background-color: #f0f0f4;">
                    <tr>
                        <td style="width: 100%; padding: 5px 10px; background-color: #f0f0f4;">
                            <p style="font-size: 11px; margin: 2px 0;"><strong>' . ($companyInfo->name ?? '') . '</strong> - ' . ($companyInfo->address ?? '') . ', ' . ($companyInfo->city ?? '') . '</p>
                            ' . ($companyInfo->phone ? '<p style="font-size: 11px; margin: 2px 0;">Tél: ' . $companyInfo->phone . ' - Email: ' . ($companyInfo->email ?? '') . '</p>' : '') . '
                        </td>
                    </tr>
                </table>
            ');

            $data = [
                'deliveryNote' => $deliveryNote,
                'order' => $deliveryNote->order,
                'company' => $companyInfo,
            ];

            // Rendre la vue en HTML
            $html = view('admin.delivery-notes.pdf.delivery-note', $data)->render();

            // Écrire le HTML dans le PDF
            $mpdf->WriteHTML($html);

            // Sauvegarder le PDF
            $fileName = "delivery_note_{$deliveryNote->delivery_number}.pdf";
            $path = "delivery_notes/{$fileName}";
            $fullPath = storage_path("app/public/{$path}");

            // Créer le dossier si nécessaire
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $mpdf->Output($fullPath, 'I');

            // Mettre à jour le bon de livraison avec le chemin du PDF
            $deliveryNote->update(['pdf_path' => $path]);

            return $path;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Update delivery note status
     */
    public function updateDeliveryNoteStatus($deliveryNoteId, $status)
    {
        $deliveryNote = DeliveryNote::findOrFail($deliveryNoteId);

        $validStatuses = ['pending', 'in_transit', 'delivered', 'failed', 'returned'];
        
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Statut invalide");
        }

        $deliveryNote->update(['status' => $status]);

        // Update order status accordingly
        if ($status === 'in_transit') {
            $deliveryNote->order->update(['status' => 'shipped']);
        } elseif ($status === 'delivered') {
            $deliveryNote->order->update([
                'status' => 'delivered',
                'delivered_at' => now()
            ]);
        }

        return $deliveryNote;
    }

    /**
     * Update tracking information
     */
    public function updateTrackingInfo($deliveryNoteId, $carrierName, $trackingNumber)
    {
        $deliveryNote = DeliveryNote::findOrFail($deliveryNoteId);

        $deliveryNote->update([
            'carrier_name' => $carrierName,
            'tracking_number' => $trackingNumber,
        ]);

        return $deliveryNote;
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered($deliveryNoteId, $recipientName = null, $signatureImage = null)
    {
        $deliveryNote = DeliveryNote::findOrFail($deliveryNoteId);

        $deliveryNote->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'recipient_name' => $recipientName,
            'signature_image' => $signatureImage,
        ]);

        // Update order status
        $deliveryNote->order->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        return $deliveryNote;
    }

    /**
     * Get delivery note statistics
     */
    public function getDeliveryNoteStats($startDate = null, $endDate = null)
    {
        $query = DeliveryNote::query();

        if ($startDate) {
            $query->where('delivery_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('delivery_date', '<=', $endDate);
        }

        $totalDeliveryNotes = $query->count();
        $pendingDeliveries = (clone $query)->where('status', 'pending')->count();
        $inTransitDeliveries = (clone $query)->where('status', 'in_transit')->count();
        $deliveredDeliveries = (clone $query)->where('status', 'delivered')->count();
        $failedDeliveries = (clone $query)->where('status', 'failed')->count();
        $returnedDeliveries = (clone $query)->where('status', 'returned')->count();

        return [
            'total_delivery_notes' => $totalDeliveryNotes,
            'pending_deliveries' => $pendingDeliveries,
            'in_transit_deliveries' => $inTransitDeliveries,
            'delivered_deliveries' => $deliveredDeliveries,
            'failed_deliveries' => $failedDeliveries,
            'returned_deliveries' => $returnedDeliveries,
        ];
    }

    /**
     * Search delivery notes
     */
    public function searchDeliveryNotes($filters = [])
    {
        $query = DeliveryNote::with(['order.user']);

        if (!empty($filters['delivery_number'])) {
            $query->where('delivery_number', 'like', '%' . $filters['delivery_number'] . '%');
        }

        if (!empty($filters['tracking_number'])) {
            $query->where('tracking_number', 'like', '%' . $filters['tracking_number'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['carrier_name'])) {
            $query->where('carrier_name', 'like', '%' . $filters['carrier_name'] . '%');
        }

        if (!empty($filters['start_date'])) {
            $query->where('delivery_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('delivery_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('delivery_date', 'desc')->paginate($filters['per_page'] ?? 20);
    }
}
