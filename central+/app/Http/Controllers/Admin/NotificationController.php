<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications de l'utilisateur
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        // Récupérer les notifications pour cet utilisateur ou son hôpital
        $notifications = Notification::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function($q2) use ($user) {
                  $q2->where('hopital_id', $user->entite_id)
                     ->whereNull('user_id');
              });
        })
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get()
        ->map(function($notif) {
            return [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->title,
                'message' => $notif->message,
                'icon' => $this->getIconForType($notif->type),
                'time' => $notif->created_at->diffForHumans(),
                'read' => $notif->read,
                'data' => $notif->data,
            ];
        });
        
        $unreadCount = Notification::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function($q2) use ($user) {
                  $q2->where('hopital_id', $user->entite_id)
                     ->whereNull('user_id');
              });
        })
        ->where('read', false)
        ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('hopital_id', $user->entite_id)
                         ->whereNull('user_id');
                  });
            })
            ->first();
        
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Récupérer les notifications pour un médecin
     */
    public function getMedecinNotifications()
    {
        $user = Auth::user();
        
        // Récupérer les notifications pour ce médecin
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $this->getIconForType($notif->type),
                    'time' => $notif->created_at->diffForHumans(),
                    'read' => $notif->read,
                    'data' => $notif->data,
                ];
            });
        
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Marquer une notification de médecin comme lue
     */
    public function markMedecinAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Obtenir l'icône selon le type de notification
     */
    private function getIconForType($type)
    {
        $icons = [
            'demande_transfert_recue' => 'inbox',
            'transfert_complete' => 'check-circle',
            'patient_nouveau' => 'user-plus',
            'nouveau_patient' => 'user-plus',
            'rendez_vous' => 'calendar-check',
            'dossier_assigne' => 'file-medical',
        ];
        
        return $icons[$type] ?? 'bell';
    }
}

