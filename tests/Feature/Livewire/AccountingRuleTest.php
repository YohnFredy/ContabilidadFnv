<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AccountingRules\Index;
use App\Models\AccountingRule;
use App\Models\Nomenclature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccountingRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_component()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('accounting-rules.index'))
            ->assertSuccessful()
            ->assertSeeLivewire(Index::class);
    }

    /** @test */
    public function it_can_create_accounting_rule()
    {
        $user = User::factory()->create();
        $account1 = Nomenclature::create(['code' => '1105', 'name' => 'Caja', 'category' => 'Activos Corrientes']);
        $account2 = Nomenclature::create(['code' => '1110', 'name' => 'Bancos', 'category' => 'Activos Corrientes']);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('name', 'Test Rule')
            ->set('nomenclature_id_1', $account1->id)
            ->set('nature_1', 'Débito')
            ->set('nomenclature_id_2', $account2->id)
            ->set('nature_2', 'Crédito')
            ->call('save');

        $this->assertDatabaseHas('accounting_rules', [
            'name' => 'Test Rule',
            'nomenclature_id_1' => $account1->id,
            'nature_1' => 'Débito',
            'nomenclature_id_2' => $account2->id,
            'nature_2' => 'Crédito',
        ]);
    }

    /** @test */
    public function it_can_delete_accounting_rule()
    {
        $user = User::factory()->create();
        $rule = AccountingRule::create(['name' => 'To Delete']);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $rule->id);

        $this->assertDatabaseMissing('accounting_rules', [
            'id' => $rule->id,
        ]);
    }
}
