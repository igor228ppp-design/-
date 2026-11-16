<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Text extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'text',
        'categorie_id',
        'user_id',
    ];

    /**
     * Связь «многие к одному»: статья относится к одной категории.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    /**
     * Связь «многие к одному»: статья принадлежит одному автору (пользователю).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
