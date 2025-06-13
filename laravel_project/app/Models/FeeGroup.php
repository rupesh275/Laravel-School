<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    use HasFactory;
    protected $table = 'fee_groups';
    // public $timestamps = false; // if updated_at is truly missing and not desired
    protected $fillable = ['name', 'fees_type', 'is_system', 'description', 'is_active', 'fine_type', 'due_date', 'fine_percentage', 'fine_amount', 'dis_name'];
}
