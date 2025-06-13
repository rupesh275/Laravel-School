<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['admission_no', 'firstname', 'lastname', 'email', 'mobileno', 'parent_id', 'is_active'];
}
