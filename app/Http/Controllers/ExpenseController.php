<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'expense_type_id' => 'required|exists:expense_types,id',
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'participants_number' => 'required|integer|min:1',
            'participants' => 'required|array',
            'participants.*.user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        foreach ($request->input('participants') as $participant) {
            $participantUser = User::findOrFail($participant['user_id']);
            if (!$user->isFriend($participantUser)) {
                return response()->json(['message' => 'All participants must be friends'], 400);
            }
        }

        $expense = Expense::create([
            'user_id' => $request->input('user_id'),
            'expense_type_id' => $request->input('expense_type_id'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
            'participants_number' => $request->input('participants_number'),
        ]);

        $amount_due = $expense->amount / $expense->participants_number;

        foreach ($request->input('participants') as $participant) {
            $expense->participants()->attach($participant['user_id'], [
                'amount_due' => $amount_due,
                'is_paid' => false,
            ]);
        }

        return response()->json(['message' => 'Expense created successfully'], 201);
    }

    public function show($id)
    {
        return Expense::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'expense_type_id' => 'sometimes|required|exists:expense_types,id',
            'description' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric',
            'date' => 'sometimes|required|date',
            'participants_number' => 'sometimes|required|integer|min:1',
        ]);

        $expense->update($request->only(['user_id', 'expense_type_id', 'description', 'amount', 'date', 'participants_number']));

        return response()->json($expense);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(null, 204);
    }
}
