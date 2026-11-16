<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    /**
     * Связь «один ко многим»: одна категория содержит множество статей (texts).
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class, 'categorie_id');
    }
}
