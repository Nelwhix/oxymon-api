<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Store a new Loan Application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request)
    {
        // Validate user input
        $user = $request->user();
        $fields = $request->validate([
            'principal' => 'required|numeric|min:6',
            'loan_duration' => 'required|string',
            'interest_type' => 'required|string'
        ]);
        $user_rating = $user->credit_rating;

        // Set different parameters depending on user's rating
        switch ($user_rating) {
            case 'A':
                $interest_rate = 0.1;
                $principal_limit = 100000000;
                $duration_limit = 6;
                break;
            case 'B':
                $interest_rate = 0.15;
                $principal_limit = 50000000;
                $duration_limit = 5;
                break;
            case 'C':
                $interest_rate = 0.2;
                $principal_limit = 10000000;
                $duration_limit = 4;
                break;
            case 'D':
                $interest_rate = 0.25;
                $principal_limit = 1000000;
                $duration_limit = 3;
                break;
            case 'E':
                $interest_rate = 0.3;
                $principal_limit = 500000;
                $duration_limit = 2;
                break;
            case 'F':
                $interest_rate = 0.35;
                $principal_limit = 100000;
                $duration_limit = 1;
                break;
        }

        if ($fields['principal'] > $principal_limit) {
            return response([
                'message' => 'You can not borrow more than ₦'.$principal_limit
            ], 422);
        }

        if ($fields['loan_duration'] > $duration_limit) {
            return response([
                'message' => 'You must pay back in '.$duration_limit. 'year(s)'
            ], 422);
        }

        // Calculate amount user will pay back in Simple or compound format
        if ($fields['interest_type'] == 'simple') {
            $total_amount_payable = $fields['principal'] * (1 + ($interest_rate * $fields['loan_duration']));
        } else {
            $total_amount_payable = $fields['principal'] * pow((1 + $interest_rate), $fields['loan_duration']);
        }

        $application_date = Carbon::now();
        if ($fields['loan_duration'] < 1) {
            $due_date = Carbon::now()->addMonths(($fields['loan_duration'] * 12));
        } else {
            $due_date = Carbon::now()->addYears($fields['loan_duration']);
        }

        $loan = Loan::create([
            'principal' => $fields['principal'],
            'interest_rate' => $interest_rate,
            'application_date' => $application_date,
            'due_date' => $due_date,
            'total_amount_payable' => $total_amount_payable,
        ]);

        // Add loan debt to user's table
        $userModel = User::find($user->id);
        $userModel->amount_owing = $total_amount_payable;
        $userModel->save();

        $response = [
            'message' => 'Application successful',
            'Loan details' => $loan,
        ];
        return response($response, 201);
    }

}
