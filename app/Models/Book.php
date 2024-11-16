<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory, HasUniqueStringIds;

    protected $fillable = ['user_id', 'category_id', 'title', 'author', 'description', 'publication_year', 'cover_image'];

    public function newUniqueId(): string
    {
        return 'BK-' . Str::uuid();
    }

    public function isValidUniqueId($value): bool
    {
        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
