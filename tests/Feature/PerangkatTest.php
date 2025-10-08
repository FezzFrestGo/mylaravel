<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Perangkat;

class PerangkatTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_delete_perangkat()
    {
    // ensure a clean schema for the test run
    $this->artisan('migrate:fresh');

        // create
        $response = $this->post(route('perangkat.store'), [
            'nama' => 'Test Router',
            'tipe' => 'Router',
            'lokasi' => 'Test Lab',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('perangkat.index'));
        $this->assertDatabaseHas('perangkat_jaringan', ['nama' => 'Test Router']);

        $p = Perangkat::where('nama', 'Test Router')->first();
        $this->assertNotNull($p);

        // delete
        $resp2 = $this->delete(route('perangkat.destroy', $p->id));
        $resp2->assertRedirect(route('perangkat.index'));
        $this->assertDatabaseMissing('perangkat_jaringan', ['nama' => 'Test Router']);
    }
}
