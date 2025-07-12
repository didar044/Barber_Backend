<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategorie extends Model
{
    protected $table="expense_categories";
    protected $fillable=['name','description'];
}
