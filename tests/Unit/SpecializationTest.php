<?php

namespace Tests\Unit;

use App\Models\Specializations\Specialization;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class SpecializationTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * Create data
     */
    protected function createData()
    {
        $specialization = new Specialization([
            'id' => 1,
            'name'    => 'Test Name',
            'description' => 'Test Description'
         ]);

        $specialization->save();

        return $specialization;
    }

    /**
     * Store test of specialization.
     *
     * @return void
     */
    public function testSpecializationStore()
    {
        $this->createData();

		$response = $this->post('admin/specializations/store', [
			'name' => 'Sample Name',
			'description' => 'Sample description'
		]);

		$response->assertJsonStructure([
            'message',
            'redirect',
        ]);

    }


    /**
     * Update test of specialization
     * 
     * @return void
     */
    public function testSpecializationUpdate()
    {
        $this->createData();

        $response = $this->post('admin/specializations/update/1', [
            'name' => 'Updated Name',
            'description' => 'Updated description'
        ]);

        $response->assertJsonStructure([
            'message',
        ]);

    }


    /**
     * Archive test of specialization
     * 
     * @return void
     */
    public function testSpecializationArchive()
    {
        $this->createData();

        $response = $this->post('admin/specializations/1/archive', []);

        $response->assertJsonStructure([
            'message',
        ]);

    }


    /**
     * Restore test of specizalization
     * 
     * @return void
     */
    public function testSpecializationRestore()
    {
        $this->createData()->delete();

        $response = $this->post('admin/specializations/1/restore', []);

        $response->assertJsonStructure([
            'message',
        ]);

    }
}
