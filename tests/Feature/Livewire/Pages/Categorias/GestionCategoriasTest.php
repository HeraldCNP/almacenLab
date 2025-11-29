<?php

use App\Models\User;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can render', function () {
    $this->actingAs($this->user);
    
    $component = Volt::test('pages.categorias.gestion-categorias');
    
    $component->assertSee('');
});
