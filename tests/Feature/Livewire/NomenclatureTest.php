<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Nomenclatures\Index;
use App\Models\Nomenclature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NomenclatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_component()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('nomenclatures.index'))
            ->assertSuccessful()
            ->assertSeeLivewire(Index::class);
    }

    /** @test */
    public function it_can_create_nomenclature()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('code', '9999')
            ->set('name', 'Test Account')
            ->set('category', 'Activos Corrientes')
            ->call('save');

        $this->assertDatabaseHas('nomenclatures', [
            'code' => '9999',
            'name' => 'Test Account',
        ]);
    }

    /** @test */
    public function it_can_edit_nomenclature()
    {
        $user = User::factory()->create();
        $nomenclature = Nomenclature::create(['code' => '8888', 'name' => 'Old Name', 'category' => 'Activos Corrientes']);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('edit', $nomenclature->id)
            ->set('name', 'New Name')
            ->call('save');

        $this->assertDatabaseHas('nomenclatures', [
            'id' => $nomenclature->id,
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function it_can_delete_nomenclature()
    {
        $user = User::factory()->create();
        $nomenclature = Nomenclature::create(['code' => '7777', 'name' => 'To Delete', 'category' => 'Pasivos Corrientes']);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $nomenclature->id);

        $this->assertDatabaseMissing('nomenclatures', [
            'id' => $nomenclature->id,
        ]);
    }
}
