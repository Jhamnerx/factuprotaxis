<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Tenant\YapeNotification;
use Illuminate\Support\Facades\Validator;

class YapeNotificationController extends Controller
{
    /**
     * Recibir notificación de Yape desde la aplicación Flutter
     */
    public function receiveNotification(Request $request): JsonResponse
    {
        try {
            // Validar los datos recibidos
            $validator = Validator::make($request->all(), [
                'sender' => 'nullable|string|max:255',
                'amount' => 'nullable|numeric|min:0',
                'date' => 'required|string',
                'message' => 'required|string',
                'rawNotification' => 'required|array',
                'rawNotification.packageName' => 'required|string',
                'rawNotification.title' => 'required|string',
                'rawNotification.timestamp' => 'required|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación fallida para notificación Yape', [
                    'errors' => $validator->errors(),
                    'data' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Convertir la fecha a Carbon
            $notificationDate = Carbon::parse($request->date);

            // Crear la notificación en la base de datos
            $notification = YapeNotification::create([
                'sender' => $request->sender,
                'amount' => $request->amount,
                'notification_date' => $notificationDate,
                'message' => $request->message,
                'raw_notification' => $request->rawNotification,
                'package_name' => $request->rawNotification['packageName'],
                'title' => $request->rawNotification['title']
            ]);

            // Log para debugging
            Log::info('Nueva notificación Yape recibida', [
                'id' => $notification->id,
                'sender' => $notification->sender,
                'amount' => $notification->amount,
                'date' => $notification->notification_date->toDateTimeString()
            ]);

            // Respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Notificación recibida y guardada correctamente',
                'data' => [
                    'id' => $notification->id,
                    'sender' => $notification->sender,
                    'amount' => $notification->formatted_amount,
                    'date' => $notification->notification_date->format('d/m/Y H:i:s'),
                    'created_at' => $notification->created_at->toDateTimeString()
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al procesar notificación Yape', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener todas las notificaciones con filtros opcionales
     */
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $query = YapeNotification::query();

            // Filtro por fecha
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $query->byDateRange($startDate, $endDate);
            }

            // Filtro por remitente
            if ($request->has('sender')) {
                $query->bySender($request->sender);
            }

            // Filtro por monto mínimo
            if ($request->has('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }

            // Filtro por monto máximo
            if ($request->has('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }

            // Ordenar por fecha descendente
            $notifications = $query->orderBy('notification_date', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener notificaciones', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las notificaciones'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de las notificaciones
     */
    public function getStatistics(Request $request): JsonResponse
    {
        try {
            $startDate = $request->has('start_date')
                ? Carbon::parse($request->start_date)
                : Carbon::now()->startOfMonth();

            $endDate = $request->has('end_date')
                ? Carbon::parse($request->end_date)
                : Carbon::now();

            $stats = [
                'total_notifications' => YapeNotification::byDateRange($startDate, $endDate)->count(),
                'total_amount' => YapeNotification::byDateRange($startDate, $endDate)->sum('amount'),
                'average_amount' => YapeNotification::byDateRange($startDate, $endDate)->avg('amount'),
                'unique_senders' => YapeNotification::byDateRange($startDate, $endDate)
                    ->distinct('sender')
                    ->whereNotNull('sender')
                    ->count('sender'),
                'top_senders' => YapeNotification::byDateRange($startDate, $endDate)
                    ->selectRaw('sender, COUNT(*) as count, SUM(amount) as total_amount')
                    ->whereNotNull('sender')
                    ->groupBy('sender')
                    ->orderBy('total_amount', 'desc')
                    ->limit(5)
                    ->get(),
                'daily_totals' => YapeNotification::byDateRange($startDate, $endDate)
                    ->selectRaw('DATE(notification_date) as date, COUNT(*) as count, SUM(amount) as total')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas'
            ], 500);
        }
    }
}
