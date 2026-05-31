<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class HospitalDoctorTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    
    protected $hospitalAdmin;
    protected $patient;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Find existing hospital admin from seeders
        $this->hospitalAdmin = User::role('hospital_admin')->first();
        
        // Create a test patient
        $this->patient = User::factory()->create([
            'name' => 'Test Patient',
            'email' => 'testpatient@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $this->patient->assignRole('patient');
    }
    
    #[Test]
    public function hospital_admin_can_view_doctors_list()
    {
        $this->actingAs($this->hospitalAdmin);
        $response = $this->get('/hospital/doctors');
        $response->assertStatus(200);
    }
    
    #[Test]
    public function hospital_admin_can_access_add_doctor_page()
    {
        $this->actingAs($this->hospitalAdmin);
        $response = $this->get('/hospital/doctors/create');
        $response->assertStatus(200);
    }
    
    #[Test]
    public function unauthorized_user_cannot_access_doctor_management()
    {
        $this->actingAs($this->patient);
        $response = $this->get('/hospital/doctors');
        
        // Should be forbidden (403)
        $response->assertStatus(403);
    }
}