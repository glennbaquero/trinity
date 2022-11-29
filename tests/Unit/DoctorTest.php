<?php

namespace Tests\Unit;

use App\Models\Users\Doctor;
use App\Models\Specializations\Specialization;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DoctorTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create Specialization
     */
    protected function createSpecialization()
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
     * Create user
     */
    protected function createUser()
    {
        $doctor = new Doctor([
            'id' => 1,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'doctor@email.com',
            'password' => \Hash::make('password'),
            'mobile_number' => '09991234567',
            'specialization_id' => 1,
         ]);
        $doctor->save();
        return $doctor;
    }

    /**
     * Login user
     */
    protected function loginUser()
    {
        return $this->post('/api/doctor/login', [
            'email' => 'doctor@email.com',
            'password' => 'password',
        ]);
    }

    /**
     * Care Registration Test
     * 
     * @return void 
     */
    public function testRegisterDoctor()
    {
        $this->createSpecialization();
        $response = $this->post('/api/doctor/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'doctor@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'mobile_number' => '09991234567',
            'specialization_id' => 1,
        ]);

        $response->assertJsonStructure([
            'token',
            'message'
        ]);
    }

    /**
     * Care Login Test
     * 
     * @return void
     */
    public function testLoginDoctor()
    {
        $this->createSpecialization();
        $this->createUser();
        
        $response = $this->loginUser();

        $response->assertJsonStructure([
            'token',
        ]);
    }

    /**
     * Update Doctor Profile
     * @WIP
     */
    // public function testUpdateProfileDoctor()
    // {
    //     $user = $this->createUser();

    //     $response = $this->post('/api/doctor/update/profile', [
    //         'fullname' => 'Test Update',
    //         'email' => 'update@email.com',
    //         'specialization_id' => 2,
    //     ], $this->headers($user))->dump();
        
    //     $response->assertStatus(200);
    // }
}
