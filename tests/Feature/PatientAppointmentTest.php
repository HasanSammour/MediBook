<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class PatientAppointmentTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    
    protected $patient;
    protected $doctor;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test patient
        $this->patient = User::factory()->create([
            'name' => 'Test Patient',
            'email' => 'testpatient@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $this->patient->assignRole('patient');
        
        // Find an existing active doctor from seeders
        $this->doctor = User::role('doctor')->where('is_active', true)->first();
    }
    
    #[Test]
    public function patient_can_view_doctors_search_page()
    {
        $this->actingAs($this->patient);
        $response = $this->get('/patient/search-doctors');
        $response->assertStatus(200);
    }
    
    #[Test]
    public function patient_can_view_hospitals_search_page()
    {
        $this->actingAs($this->patient);
        $response = $this->get('/patient/search-hospitals');
        $response->assertStatus(200);
    }
    
    #[Test]
    public function patient_cannot_book_appointment_without_login()
    {
        // Not logged in
        $response = $this->post('/patient/book/store', [
            'doctor_id' => 1,
            'appointment_date' => Carbon::tomorrow()->format('Y-m-d'),
            'time' => '10:00',
        ]);
        
        // Should redirect to login (302)
        $response->assertStatus(302);
    }
}