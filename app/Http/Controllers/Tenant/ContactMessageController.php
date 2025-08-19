<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\ContactMessage;
use App\Http\Resources\Tenant\ContactMessageResource;
use App\Http\Resources\Tenant\ContactMessageCollection;

class ContactMessageController extends Controller
{
    public function index()
    {
        return view('tenant.contact_messages.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'email' => 'Email',
            'phone' => 'Teléfono',
            'status' => 'Estado',
            'created_at' => 'Fecha',
        ];
    }

    public function records(Request $request)
    {
        $records = ContactMessage::latest();

        // Filtros
        if ($request->has('status') && $request->status !== '') {
            $records->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $records->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        return new ContactMessageCollection($records->paginate(config('tenant.items_per_page', 20)));
    }

    public function record($id)
    {
        $record = ContactMessage::findOrFail($id);

        // Marcar como leído si está pendiente
        if ($record->status === 'pending') {
            $record->markAsRead();
        }

        return new ContactMessageResource($record);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $message = ContactMessage::findOrFail($id);

        if ($request->status === 'replied') {
            $message->markAsReplied($request->admin_notes);
        } else {
            $message->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);

            if ($request->status === 'read' && !$message->read_at) {
                $message->update(['read_at' => now()]);
            }
        }

        return [
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ];
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return [
            'success' => true,
            'message' => 'Mensaje eliminado correctamente'
        ];
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_read,mark_replied,mark_closed',
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);

        $messages = ContactMessage::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'delete':
                $messages->delete();
                $message = 'Mensajes eliminados correctamente';
                break;
            case 'mark_read':
                $messages->update(['status' => 'read', 'read_at' => now()]);
                $message = 'Mensajes marcados como leídos';
                break;
            case 'mark_replied':
                $messages->update(['status' => 'replied', 'replied_at' => now()]);
                $message = 'Mensajes marcados como respondidos';
                break;
            case 'mark_closed':
                $messages->update(['status' => 'closed']);
                $message = 'Mensajes cerrados correctamente';
                break;
        }

        return [
            'success' => true,
            'message' => $message
        ];
    }

    public function stats()
    {
        return [
            'total' => ContactMessage::count(),
            'pending' => ContactMessage::where('status', 'pending')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)->count(),
        ];
    }
}
