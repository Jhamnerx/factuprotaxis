<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\YapeNotification;
use App\Http\Resources\Tenant\YapeNotificationResource;
use App\Http\Resources\Tenant\YapeNotificationCollection;

class YapeNotificationController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.yape_notifications.index');
    }

    public function columns()
    {
        return [
            'sender' => 'Remitente',
            'amount' => 'Monto',
            'notification_date' => 'Fecha de Notificación',
            'message' => 'Mensaje',
            'codigo_seguridad' => 'Código de Seguridad',
            'is_used' => 'Estado',
            'used_at' => 'Fecha de Uso',
            'actions' => 'Acciones'
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);
        return new YapeNotificationCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function getRecords(Request $request)
    {
        $records = YapeNotification::query();

        if ($request->column) {
            switch ($request->column) {
                case 'sender':
                    $records->where('sender', 'like', "%{$request->value}%");
                    break;
                case 'amount':
                    $records->where('amount', $request->value);
                    break;
                case 'notification_date':
                    $records->whereDate('notification_date', 'like', "%{$request->value}%");
                    break;
                case 'message':
                    $records->where('message', 'like', "%{$request->value}%");
                    break;
                case 'codigo_seguridad':
                    $records->where('codigo_seguridad', 'like', "%{$request->value}%");
                    break;
                case 'is_used':
                    $value = $request->value === 'usado' ? 1 : ($request->value === 'disponible' ? 0 : $request->value);
                    $records->where('is_used', $value);
                    break;
                default:
                    $records->where($request->column, 'like', "%{$request->value}%");
                    break;
            }
        }

        // Filtros adicionales
        if ($request->has('date_start') && $request->date_start) {
            $records->whereDate('notification_date', '>=', $request->date_start);
        }

        if ($request->has('date_end') && $request->date_end) {
            $records->whereDate('notification_date', '<=', $request->date_end);
        }

        if ($request->has('status') && $request->status !== '') {
            $records->where('is_used', $request->status);
        }

        if ($request->has('min_amount') && $request->min_amount) {
            $records->where('amount', '>=', $request->min_amount);
        }

        if ($request->has('max_amount') && $request->max_amount) {
            $records->where('amount', '<=', $request->max_amount);
        }

        return $records->orderBy('notification_date', 'desc');
    }

    /**
     * Marcar una notificación como usada
     */
    public function markAsUsed(Request $request, $id)
    {
        try {
            $request->validate([
                'subscription_invoice_id' => 'nullable|integer'
            ]);

            $notification = YapeNotification::findOrFail($id);

            if ($notification->is_used) {
                return [
                    'success' => false,
                    'message' => 'Esta notificación ya está siendo utilizada'
                ];
            }

            $notification->markAsUsed($request->subscription_invoice_id);

            return [
                'success' => true,
                'message' => 'Notificación marcada como utilizada'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al marcar la notificación: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public function statistics()
    {
        try {
            $total = YapeNotification::count();
            $used = YapeNotification::used()->count();
            $unused = YapeNotification::unused()->count();
            $totalAmount = YapeNotification::sum('amount');
            $usedAmount = YapeNotification::used()->sum('amount');

            return [
                'success' => true,
                'data' => [
                    'total_notifications' => $total,
                    'used_notifications' => $used,
                    'unused_notifications' => $unused,
                    'total_amount' => $totalAmount,
                    'used_amount' => $usedAmount,
                    'unused_amount' => $totalAmount - $usedAmount
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ];
        }
    }
}
