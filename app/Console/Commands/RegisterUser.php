<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register {name} {email} {password} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a new user in format: Name.Surname, name@example.com, password, role=admin,editor,user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->argument('role');

        if (!in_array($role, ['admin', 'editor', 'user'])) {
            $this->error('Invalid role. Allowed roles: admin,editor,user');
            return;
        }

        if (!str_contains($name, '.')) {
            $this->error('Invalid name format. Allowed format: Name.Surname');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format.');
            return;
        }

        DB::beginTransaction();
        try {
            $user = User::query()
                ->create([
                    'name' => str_replace('.', ' ', $name),
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);

            $userRole = Role::query()
                ->where('name', $role)
                ->first();

            $user->roles()->attach($userRole);

            DB::commit();

            $this->info('User registered successfully.');

        } catch (Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage());
        }
    }
}
