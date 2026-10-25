
<?php

namespace Tests\Feature;

use Tests\TestCase;

class PatientApiTest extends TestCase
{
    public function test_patient_endpoint_returns_success(): void
    {
        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'count',
            'data',
        ]);
    }
}
