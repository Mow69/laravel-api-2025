<?php

use App\Models\Profil;
use App\Models\User;
use App\Models\Status;
use App\Enums\StatusType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('unauthenticated users can only see active profiles', function () {
    $activeStatus = Status::where('name', StatusType::ACTIVE->value)->first();
    $inactiveStatus = Status::where('name', StatusType::INACTIVE->value)->first();

    Profil::factory(3)->create(['status_id' => $activeStatus->id]);
    Profil::factory(2)->create(['status_id' => $inactiveStatus->id]);

    $this->getJson('/api/profils')
        ->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'firstname',
                    'lastname',
                    'image',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ])
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.0.id', fn ($id) => is_integer($id))
        ->assertJsonPath('data.0.firstname', fn ($firstname) => is_string($firstname))
        ->assertJsonPath('data.0.lastname', fn ($lastname) => is_string($lastname))
        ->assertJsonPath('data.0.image', fn ($image) => is_string($image))
        ->assertJsonMissing(['data.0.status']);
});

test('authenticated users can see all profiles with status', function () {
    $user = User::factory()->create();
    Profil::factory(5)->create();

    $this->actingAs($user)
        ->getJson('/api/profils')
        ->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'firstname',
                    'lastname',
                    'image',
                    'status'
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ])
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('data.0.id', fn ($id) => is_integer($id))
        ->assertJsonPath('data.0.firstname', fn ($firstname) => is_string($firstname))
        ->assertJsonPath('data.0.lastname', fn ($lastname) => is_string($lastname))
        ->assertJsonPath('data.0.image', fn ($image) => is_string($image))
        ->assertJsonPath('data.0.status', fn ($status) => is_string($status));
});

test('pagination works correctly', function () {
    $activeStatus = Status::where('name', StatusType::ACTIVE->value)->first();

    Profil::factory(45)->create(['status_id' => $activeStatus->id]);

    expect(Profil::count())->toBe(45);

    // Act & Assert
    $this->getJson('/api/profils?page=2')
        ->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ])
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('current_page', 2)
                ->where('per_page', 15)
                ->where('total', 45)
                ->where('from', 16)
                ->where('to', 30)
                ->where('last_page', 3)
                ->etc()
        );
});

test('authenticated user can create a profile with image', function () {
    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('avatar.jpg');

    $payload = [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'image' => $image,
        'status' => StatusType::ACTIVE->value
    ];

    $this->actingAs($user)
        ->postJson('/api/profils', $payload)
        ->assertOk()
        ->assertJsonStructure([
            'profil' => [
                'id',
                'firstname',
                'lastname',
                'image',
                'status_id',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('profil', fn ($json) =>
                $json->where('firstname', 'John')
                    ->where('lastname', 'Doe')
                    ->whereType('id', 'integer')
                    ->whereType('status_id', 'integer')
                    ->whereType('image', 'string')
                    ->whereType('created_at', 'string')
                    ->whereType('updated_at', 'string')
            )
        );

    $profil = Profil::latest()->first();
    expect($profil)
        ->firstname->toBe('John')
        ->lastname->toBe('Doe')
        ->getFirstMedia('avatar')->not->toBeNull();
});

test('profile creation validates required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/profils', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['firstname', 'lastname', 'image', 'status']);
});

test('authenticated user can update a profile', function () {
    $user = User::factory()->create();
    $profil = Profil::factory()->create();
    $newImage = UploadedFile::fake()->image('new-avatar.jpg');

    $payload = [
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'image' => $newImage,
        'status' => StatusType::PENDING->value
    ];

    $this->actingAs($user)
        ->putJson("/api/profils/{$profil->id}", $payload)
        ->assertOk();

    $profil->refresh();
    expect($profil)
        ->firstname->toBe('Jane')
        ->lastname->toBe('Smith')
        ->getFirstMedia('avatar')->not->toBeNull();
});

test('authenticated user can delete a profile', function () {
    $user = User::factory()->create();
    $profil = Profil::factory()->create();

    $this->actingAs($user)
        ->deleteJson("/api/profils/{$profil->id}")
        ->assertNoContent();

    expect($profil->fresh())->toBeNull();
    expect($profil->getFirstMedia('avatar'))->toBeNull();
});
