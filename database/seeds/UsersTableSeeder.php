<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UsersTableSeeder extends Seeder
{
    public const USER_EMAIL = 'user@email.tld';
    public const USER_PASSWORD = 'password';
    public const USER_TOKEN = 'secret';

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function run()
    {
        (new User([
            'name'              => 'Mr. User',
            'email'             => self::USER_EMAIL,
            'email_verified_at' => now(),
            'password'          => Hash::make(self::USER_PASSWORD),
            'api_token'         => hash('sha256', self::USER_TOKEN), // ! matches default algorithm
            'remember_token'    => Str::random(10),
        ]))->saveOrFail();

        factory(User::class, 5)->create();
    }
}
