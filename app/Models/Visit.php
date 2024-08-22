<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /// ATTRIBUTES

    /**
     * Campos que se pueden asignar de forma masiva.
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'latitude',
        'longitude',
    ];

    /// HOOKS
    /// ELOQUENT
    /// PRIVATE FUNCTIONS
    /// PUBLIC FUNCTIONS
    /// STATIC FUNCTIONS

}
