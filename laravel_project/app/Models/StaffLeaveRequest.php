<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffLeaveRequest extends Model
{
    use HasFactory;
    use SoftDeletes; // Ensuring SoftDeletes is kept and correctly placed
    protected $table = 'staff_leave_request';
    protected $fillable = ['staff_id', 'leave_type_id', 'leave_from', 'leave_to', 'leave_days', 'employee_remark', 'admin_remark', 'status', 'applied_by', 'document_file', 'date', 'session_id', 'created_by', 'updated_by'];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
