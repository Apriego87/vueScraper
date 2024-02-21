<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewspaperModel extends Model
{
    use HasFactory;

    protected $table = 'newspapers';
    protected $fillable = [
        'name',
        'link',
    ];
}
