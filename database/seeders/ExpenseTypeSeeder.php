<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseType;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenseTypes = [
            ['name' => 'Alimentação'],
            ['name' => 'Transporte'],
            ['name' => 'Lazer'],
        ];

        foreach ($expenseTypes as $type) {
            ExpenseType::firstOrCreate($type);
        }
    }
}