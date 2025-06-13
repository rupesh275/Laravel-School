<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'logs';
    public $timestamps = false; // Or customize created_at to 'time' and remove updated_at if not present
    protected $fillable = ['message', 'record_id', 'user_id', 'action', 'ip_address', 'platform', 'agent', 'time'];
}
