<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Schema;

test('provides the user management schema foundation', function () {
    expect(Schema::hasColumns('users', [
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_active',
        'timezone',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ]))->toBeTrue();
});

test('creates active users by default and can create inactive users', function () {
    $activeUser = User::factory()->create();
    $inactiveUser = User::factory()->inactive()->create();

    expect($activeUser->is_active)
        ->toBeTrue()
        ->and($inactiveUser->is_active)
        ->toBeFalse();
});

test('soft deletes users', function () {
    $user = User::factory()->create();

    $user->delete();

    expect($user->trashed())
        ->toBeTrue()
        ->and(User::query()->find($user->getKey()))
        ->toBeNull()
        ->and(User::withTrashed()->find($user->getKey()))
        ->not->toBeNull();
});

test('seeds development data idempotently', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::query()->where('email', 'test@example.com')->count())->toBe(1);
});
