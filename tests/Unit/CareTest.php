<?php

namespace Tests\Unit;

use App\Models\Users\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CareTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create data
     */
    protected function createData()
    {
        $user = new User([
            'first_name' => 'Test',
            'last_name' => 'Name',
            'email' => 'care@email.com',
            'password' => \Hash::make('password'),
            'mobile_number' => '09991234567'
         ]);

        $user->save();

        return $user;
    }

    /**
     * Care Registration Test
     * 
     * @return void 
     */
    public function testRegisterCare()
    {
        $response = $this->post('/api/care/register', [
            'first_name' => 'Care',
            'last_name' => 'User',
            'email' => 'care@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'mobile_number' => '09991234567',
            'region_id' => 1,
            'province_id' => 1,
            'city_id' => 1,
            'unit' => 'Unit',
            'building' => 'Building',
            'street' => 'Street',
            'zip' => 'Zip',
            'landmark' => 'landmark'
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
    public function testLoginCare()
    {
        $this->createData();
        
        $response = $this->post('/api/care/login', [
            'email' => 'care@email.com',
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'token',
        ]);
    }
}
