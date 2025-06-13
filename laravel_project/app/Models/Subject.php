<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'type', 'is_active', 'short_name', 'long_name', 'parent_id', 'SubjectType'];
}
