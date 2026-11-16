<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Связь «один ко многим»: одна роль может быть назначена множеству пользователей.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
