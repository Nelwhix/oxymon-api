<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'principal',
        'user_id',
        'interest_rate',
        'total_amount_payable',
        'application_date',
        'due_date',
        'amount_owing',
    ];

    /**
     * Get the user that owns loan
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
