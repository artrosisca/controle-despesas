<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expense_type_id',
        'description',
        'amount',
        'date',
        'participants_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'shared_expense_participants')
                    ->withPivot('amount_due', 'is_paid')
                    ->withTimestamps();
    }
}
