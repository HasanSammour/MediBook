<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{

    /**
     * Get random date of birth (age between 18 and 80)
     */
    private function getRandomDob()
    {
        $minAge = 18;
        $maxAge = 80;
        $years = rand($minAge, $maxAge);
        $months = rand(1, 12);
        $days = rand(1, 28);

        return now()->subYears($years)->subMonths($months)->subDays($days)->format('Y-m-d');
    }

    /**
     * Get random gender
     */
    private function getRandomGender()
    {
        $genders = ['male', 'female', 'other'];
        return $genders[array_rand($genders)];
    }

    public function run(): void
    {
        $faker = Faker::create();
        $hospitals = Hospital::all();

        // Available profile images arrays
        $adminImages = [
            'images/admin/admin.jpeg',
        ];
        
        $hospitalAdminImages = [
            'images/hospital_admins/admin1.jpg',
            'images/hospital_admins/admin2.jpg',
            'images/hospital_admins/admin3.jpg',
            'images/hospital_admins/admin4.jpg',
        ];
        
        $doctorImages = [
            'images/doctors/doctor1.jpg',
            'images/doctors/doctor2.jpg',
            'images/doctors/doctor3.jpg',
            'images/doctors/doctor4.jpg',
        ];
        
        $patientImages = [
            'images/patients/patient1.jpg',
            'images/patients/patient2.jpg',
            'images/patients/patient3.jpg',
        ];

        // Track used emails to avoid duplicates
        $usedEmails = [];

        // ============================================
        // SYSTEM ADMIN (1 user)
        // ============================================
        $systemAdmin = User::create([
            'name' => 'Hasan Sammour',
            'email' => 'admin@system.com',
            'password' => Hash::make('password'),
            'phone' => '+970 (59) 000-0001',
            'profile_image' => $adminImages[0],
            'gender' => 'male', 
            'date_of_birth' => '2003-01-24',
            'is_active' => true,
            'hospital_id' => null,
            'specialization' => null,
            'consultation_fee' => null,
            'availability' => null,
            'is_available' => null,
            'email_verified_at' => now(),
        ]);
        $systemAdmin->assignRole('system_admin');
        $usedEmails[] = 'admin@system.com';

        // ============================================
        // HOSPITAL ADMINS (1 per hospital)
        // ============================================
        $hospitalAdminNames = [
            'Ahmed Al-Hassan', 'Mohammed Abu Odeh', 'Nadia Al-Masri', 'Omar Al-Shawa', 'Fatima Al-Astal',
            'Hassan Al-Najjar', 'Rana Al-Batta', 'Yousef Al-Qudra', 'Lina Al-Helo', 'Mahmoud Al-Buhaisi',
            'Samira Al-Khaldi', 'Ibrahim Al-Yazji', 'Maysa Al-Dahdouh', 'Khalil Al-Ajlouni', 'Eman Al-Bahtiti'
        ];

        foreach ($hospitals as $index => $hospital) {
            $adminName = $hospitalAdminNames[$index % count($hospitalAdminNames)];
            $adminEmail = strtolower(preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $adminName))) . '@' . preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $hospital->name)) . '.ps';
            $adminEmail = strtolower($adminEmail);
            
            // Ensure unique email
            $counter = 1;
            $originalEmail = $adminEmail;
            while (in_array($adminEmail, $usedEmails)) {
                $adminEmail = str_replace('.ps', $counter . '.ps', $originalEmail);
                $counter++;
            }
            
            $hospitalAdmin = User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'phone' => '+970 (59) ' . rand(1000000, 9999999),
                'profile_image' => $hospitalAdminImages[array_rand($hospitalAdminImages)],
                'gender' => $this->getRandomGender(), 
                'date_of_birth' => $this->getRandomDob(),
                'is_active' => true,
                'hospital_id' => $hospital->id,
                'specialization' => null,
                'consultation_fee' => null,
                'availability' => null,
                'is_available' => null,
                'email_verified_at' => now(),
            ]);
            $hospitalAdmin->assignRole('hospital_admin');
            $usedEmails[] = $adminEmail;
        }

        // ============================================
        // DOCTORS (50 doctors)
        // ============================================
        $specializations = [
            'Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Dermatology', 
            'Psychiatry', 'Ophthalmology', 'Dentistry', 'Gynecology', 'Urology',
            'Radiology', 'Oncology', 'Emergency Medicine', 'Internal Medicine', 'Endocrinology',
            'Gastroenterology', 'Nephrology', 'Pulmonology', 'Rheumatology', 'Hematology'
        ];

        $doctorFirstNames = ['Ahmed', 'Mohammed', 'Omar', 'Hassan', 'Mahmoud', 'Ibrahim', 'Khalil', 'Yousef', 'Ali', 'Hussein'];
        $doctorLastNames = ['Abu Odeh', 'Al-Hassan', 'Al-Shawa', 'Al-Najjar', 'Al-Buhaisi', 'Al-Yazji', 'Al-Ajlouni', 'Al-Qudra', 'Al-Khaldi', 'Al-Helo'];
        $doctorFemaleFirst = ['Nadia', 'Fatima', 'Rana', 'Lina', 'Maysa', 'Eman', 'Samira', 'Rasha', 'Abeer', 'Huda'];

        // Break time default (12:00 - 13:00)
        $defaultBreakStart = '12:00';
        $defaultBreakEnd = '13:00';

        function generateTimeSlotsForSeed($start, $end, $breakStart, $breakEnd)
        {
            $slots = [];
            $current = strtotime($start);
            $endTime = strtotime($end);
            $breakStartTime = strtotime($breakStart);
            $breakEndTime = strtotime($breakEnd);

            while ($current < $endTime) {
                $currentTime = date('H:i', $current);

                // Skip break time
                if ($current >= $breakStartTime && $current < $breakEndTime) {
                    $current = strtotime('+30 minutes', $current);
                    continue;
                }

                $slots[] = $currentTime;
                $current = strtotime('+30 minutes', $current);
            }

            return $slots;
        }

        for ($i = 0; $i < 50; $i++) {
            $hospital = $hospitals->random();
            $isFemale = $i % 3 == 0;
            $firstName = $isFemale ? $doctorFemaleFirst[array_rand($doctorFemaleFirst)] : $doctorFirstNames[array_rand($doctorFirstNames)];
            $lastName = $doctorLastNames[array_rand($doctorLastNames)];
            $specialization = $specializations[array_rand($specializations)];

            $hospitalName = preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $hospital->name));
            $email = strtolower('dr.' . $firstName . '.' . $lastName) . '@' . $hospitalName . '.ps';

            // Ensure unique email
            $counter = 1;
            $originalEmail = $email;
            while (in_array($email, $usedEmails)) {
                $email = str_replace('.ps', $counter . '.ps', $originalEmail);
                $counter++;
            }

            // Define working hours for this doctor (random but reasonable)
            $workingHoursData = [];
            $availabilityData = [];

            // Random working days (most doctors work Mon-Fri, some work Sat)
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                // Monday to Friday: working, Saturday: 50% chance, Sunday: closed
                $isWorking = false;
                $startTime = '09:00';
                $endTime = '17:00';

                if (in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])) {
                    $isWorking = true;
                    // Random start between 8-10 AM, end between 4-6 PM
                    $startTime = rand(8, 10) . ':00';
                    $endTime = rand(16, 18) . ':00';
                } elseif ($day == 'saturday') {
                    $isWorking = (rand(1, 100) <= 50); // 50% chance to work Saturday
                    if ($isWorking) {
                        $startTime = '10:00';
                        $endTime = '14:00';
                    }
                } else {
                    $isWorking = false;
                }

                if ($isWorking) {
                    // Store raw working hours
                    $workingHoursData[$day] = [
                        'enabled' => true,
                        'start' => $startTime,
                        'end' => $endTime
                    ];

                    // Generate slots with break time applied
                    $slots = generateTimeSlotsForSeed($startTime, $endTime, $defaultBreakStart, $defaultBreakEnd);
                    $availabilityData[$day] = $slots;
                } else {
                    $workingHoursData[$day] = [
                        'enabled' => false,
                        'start' => null,
                        'end' => null
                    ];
                    $availabilityData[$day] = [];
                }
            }

            $doctor = User::create([
                'name' => 'Dr. ' . $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => '+970 (59) ' . rand(1000000, 9999999),
                'profile_image' => $doctorImages[array_rand($doctorImages)],
                'gender' => $isFemale ? 'female' : $this->getRandomGender(), 
                'date_of_birth' => $this->getRandomDob(),
                'is_active' => true,
                'hospital_id' => $hospital->id,
                'specialization' => $specialization,
                'consultation_fee' => rand(50, 300),
                'working_hours' => json_encode($workingHoursData),  // ← العمود الجديد
                'availability' => json_encode($availabilityData),
                'is_available' => true,
                'email_verified_at' => now(),
            ]);
            $doctor->assignRole('doctor');
            $usedEmails[] = $email;
        }

        // ============================================
        // PATIENTS (400+ users)
        // ============================================
        $patientFirstNames = [
            'Mohammed', 'Ahmed', 'Yousef', 'Hassan', 'Mahmoud', 'Ibrahim', 'Khalil', 'Ali', 'Hussein', 'Omar',
            'Nadia', 'Fatima', 'Rana', 'Lina', 'Maysa', 'Eman', 'Samira', 'Abeer', 'Huda', 'Reem',
            'Adel', 'Bassem', 'Tamer', 'Rami', 'Sami', 'Walid', 'Nasser', 'Jamil', 'Fadi', 'Rafiq',
            'Mona', 'Sahar', 'Dina', 'Lama', 'Nour', 'Haya', 'Tala', 'Sara', 'Mariam', 'Layla'
        ];
        
        $patientLastNames = [
            'Abu Odeh', 'Al-Hassan', 'Al-Shawa', 'Al-Najjar', 'Al-Buhaisi', 'Al-Yazji', 'Al-Ajlouni', 
            'Al-Qudra', 'Al-Khaldi', 'Al-Helo', 'Al-Masri', 'Al-Astal', 'Al-Batta', 'Al-Dahdouh', 
            'Al-Bahtiti', 'Al-Madhoun', 'Al-Sarraj', 'Al-Haddad', 'Al-Masoud', 'Al-Agha'
        ];

        for ($i = 0; $i < 400; $i++) {
            $firstName = $patientFirstNames[array_rand($patientFirstNames)];
            $lastName = $patientLastNames[array_rand($patientLastNames)];
            $email = strtolower($firstName . '.' . $lastName . '.' . ($i + 1)) . '@patient.ps';
            
            $patient = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => '+970 (56) ' . rand(1000000, 9999999),
                'profile_image' => $patientImages[array_rand($patientImages)],
                'gender' => $this->getRandomGender(),  // ADD THIS
                'date_of_birth' => $this->getRandomDob(),  // ADD THIS
                'is_active' => true,
                'hospital_id' => null,
                'specialization' => null,
                'consultation_fee' => null,
                'availability' => null,
                'is_available' => null,
                'email_verified_at' => now(),
            ]);
            $patient->assignRole('patient');
            $usedEmails[] = $email;
        }

        $this->command->info('====================================');
        $this->command->info('Users seeded successfully!');
        $this->command->info('System Admins: 1');
        $this->command->info('Hospital Admins: ' . $hospitals->count());
        $this->command->info('Doctors: 50');
        $this->command->info('Patients: ' . User::role('patient')->count());
        $this->command->info('Total Users: ' . User::count());
        $this->command->info('====================================');
    }
}