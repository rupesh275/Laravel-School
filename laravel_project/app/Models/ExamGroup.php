<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamGroup extends Model
{
    use HasFactory;
    protected $table = 'exam_groups';
    protected $fillable = ['name', 'exam_type', 'description', 'exam_type_id', 'is_active'];
}
