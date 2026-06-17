<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestCredentialsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin Users ──────────────────────────────────────────────────

        // 1. Super Admin (Principal)
        $superAdmin = User::updateOrCreate(
            ['email' => 'jinnahschooldegreecollege@gmail.com'],
            ['name' => 'Arif Ali', 'password' => Hash::make('admin@1234')]
        );
        $superAdmin->syncRoles(['super_admin']);

        // 2. Developer
        $developer = User::updateOrCreate(
            ['email' => 'developer@jdca.edu.pk'],
            ['name' => 'System Developer', 'password' => Hash::make('dev@1234')]
        );
        $developer->syncRoles(['Developer']);

        // 3. Panel User (e.g. office staff)
        $staff = User::updateOrCreate(
            ['email' => 'staff@jdca.edu.pk'],
            ['name' => 'Office Staff', 'password' => Hash::make('staff@1234')]
        );
        $staff->syncRoles(['panel_user']);

        // ── Student Portal Passwords ─────────────────────────────────────
        // Set explicit passwords for 5 test students
        $studentCredentials = [
            'CS-2024-0001' => 'student@1234',
            'CS-2024-0002' => 'student@1234',
            'CS-2023-0001' => 'student@1234',
            'CS-2023-0002' => 'student@1234',
            'CS-2022-0001' => 'student@1234',
        ];

        foreach ($studentCredentials as $roll => $password) {
            Student::where('roll_number', $roll)
                ->update(['portal_password' => Hash::make($password)]);
        }

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║              TEST LOGIN CREDENTIALS                          ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  ADMIN PANEL  →  http://127.0.0.1:8000/admin                ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: super_admin (Full Access)                             ║');
        $this->command->info('║  Email:    jinnahschooldegreecollege@gmail.com               ║');
        $this->command->info('║  Password: admin@1234                                        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: Developer (All modules)                               ║');
        $this->command->info('║  Email:    developer@jdca.edu.pk                             ║');
        $this->command->info('║  Password: dev@1234                                          ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: panel_user (Limited access)                           ║');
        $this->command->info('║  Email:    staff@jdca.edu.pk                                 ║');
        $this->command->info('║  Password: staff@1234                                        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  STUDENT PORTAL  →  http://127.0.0.1:8000/portal/login      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Student: Muhammad Ali Khan                                  ║');
        $this->command->info('║  Roll No: CS-2024-0001   Password: student@1234             ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  Student: Fatima Zahra                                       ║');
        $this->command->info('║  Roll No: CS-2024-0002   Password: student@1234             ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  Student: Hamza Raza                                         ║');
        $this->command->info('║  Roll No: CS-2023-0001   Password: student@1234             ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
    }
}
