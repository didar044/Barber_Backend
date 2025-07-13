<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expense\ExpenseCategorie;

class Expense extends Model
{
    protected $table="expenses";
    protected $fillable=['expense_category_id','reference_number','expense_for','amount','expense_date','description'];
    public function expensecategorie(){
          return $this->belongsTo(ExpenseCategorie::class,'expense_category_id');
    }
}
