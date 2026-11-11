<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
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
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
