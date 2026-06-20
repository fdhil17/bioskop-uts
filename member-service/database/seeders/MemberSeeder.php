<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'phone' => '081234567890'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@example.com', 'phone' => '081298765432'],
            ['name' => 'Agus Setiawan', 'email' => 'agus.setiawan@example.com', 'phone' => '081312345678'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@example.com', 'phone' => '081387654321'],
            ['name' => 'Rina Melati', 'email' => 'rina.melati@example.com', 'phone' => '085612345678'],
            ['name' => 'Hendra Pratama', 'email' => 'hendra.pratama@example.com', 'phone' => '085687654321'],
            ['name' => 'Nia Ramadhani', 'email' => 'nia.ramadhani@example.com', 'phone' => '081112345678'],
            ['name' => 'Andi Saputra', 'email' => 'andi.saputra@example.com', 'phone' => '081187654321'],
            ['name' => 'Lestari Putri', 'email' => 'lestari.putri@example.com', 'phone' => '082112345678'],
            ['name' => 'Rizki Aditya', 'email' => 'rizki.aditya@example.com', 'phone' => '082187654321'],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(
                ['email' => $member['email']],
                $member
            );
        }
    }
}
