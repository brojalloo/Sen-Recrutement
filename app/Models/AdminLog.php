<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'target_type',
        'target_id',
        'old_values',
        'new_values',
    ];

    public const ACTIONS = [
        'login' => 'Connexion',
        'logout' => 'Déconnexion',
        'create' => 'Création',
        'update' => 'Modification',
        'delete' => 'Suppression',
        'view' => 'Consultation',
        'export' => 'Export',
        'import' => 'Import',
        'status_change' => 'Changement de statut',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
