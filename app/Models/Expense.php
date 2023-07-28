<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['created_by_user_with_id', 'expense_category', 'amount', 'entry_date', 'created_at'];
}
