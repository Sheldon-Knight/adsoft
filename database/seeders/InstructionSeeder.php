<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Instruction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Instruction::factory()->times(100)->create();
    }
}
