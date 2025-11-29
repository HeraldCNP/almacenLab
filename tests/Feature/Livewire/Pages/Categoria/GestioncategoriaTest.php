<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('pages.categoria.gestion-categoria');

    $component->assertSee('');
});
