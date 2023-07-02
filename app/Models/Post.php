<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'publish',
    ];

    /**
     * Interact with the post's publish.
     *
     * @return Attribute
     */
    protected function publish(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != 0,
            set: fn ($value) => $value != '' ? 1 : 0,
        );
    }

    /**
     * Interact with the post's created at.
     *
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('Y-m-d H:i:s', strtotime($value)),
        );
    }
}
