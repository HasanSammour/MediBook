<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            [
                'name' => 'Al-Shifa Hospital',
                'address' => 'Al-Jala Street, Rimal District, Gaza City',
                'phone' => '+970 (8) 283-8888',
                'email' => 'info@alshifa.ps',
                'logo' => 'images/hospital_logo/hospital1.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Indonesian Hospital',
                'address' => 'Beit Lahia, North Gaza',
                'phone' => '+970 (8) 259-6666',
                'email' => 'contact@indonesianhospital.ps',
                'logo' => 'images/hospital_logo/hospital2.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Aqsa Martyrs Hospital',
                'address' => 'Deir al-Balah, Central Gaza',
                'phone' => '+970 (8) 253-7777',
                'email' => 'info@al-aqsahospital.ps',
                'logo' => 'images/hospital_logo/hospital3.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Nasser Medical Complex',
                'address' => 'Khan Younis, Southern Gaza',
                'phone' => '+970 (8) 205-6000',
                'email' => 'contact@nasserhospital.ps',
                'logo' => 'images/hospital_logo/hospital4.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'European Gaza Hospital',
                'address' => 'Khan Younis, Southern Gaza',
                'phone' => '+970 (8) 206-4000',
                'email' => 'info@europeangaza.ps',
                'logo' => 'images/hospital_logo/hospital5.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Rantisi Pediatric Hospital',
                'address' => 'Al-Nasr Street, Gaza City',
                'phone' => '+970 (8) 283-4444',
                'email' => 'info@rantisi.ps',
                'logo' => 'images/hospital_logo/hospital6.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Quds Hospital',
                'address' => 'Tal Al-Hawa, Gaza City',
                'phone' => '+970 (8) 288-7777',
                'email' => 'contact@alqudshospital.ps',
                'logo' => 'images/hospital_logo/hospital1.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Gaza European Hospital',
                'address' => 'Beit Hanoun, North Gaza',
                'phone' => '+970 (8) 259-5555',
                'email' => 'info@gazaeuropean.ps',
                'logo' => 'images/hospital_logo/hospital2.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Helal Al-Emirati Hospital',
                'address' => 'Rafah, Southern Gaza',
                'phone' => '+970 (8) 213-9999',
                'email' => 'info@emiratihospital.ps',
                'logo' => 'images/hospital_logo/hospital3.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Beit Hanoun Hospital',
                'address' => 'Beit Hanoun, North Gaza',
                'phone' => '+970 (8) 259-1234',
                'email' => 'info@beithanoun.ps',
                'logo' => 'images/hospital_logo/hospital4.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Amal Hospital',
                'address' => 'Khan Younis, Southern Gaza',
                'phone' => '+970 (8) 205-8000',
                'email' => 'contact@alamalhospital.ps',
                'logo' => 'images/hospital_logo/hospital5.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Palestinian Red Crescent Hospital',
                'address' => 'Gaza City',
                'phone' => '+970 (8) 282-4444',
                'email' => 'info@prcs.ps',
                'logo' => 'images/hospital_logo/hospital6.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Mezan Hospital',
                'address' => 'Deir al-Balah, Central Gaza',
                'phone' => '+970 (8) 253-8888',
                'email' => 'info@almezan.ps',
                'logo' => 'images/hospital_logo/hospital1.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Yafa Hospital',
                'address' => 'Gaza City',
                'phone' => '+970 (8) 288-1234',
                'email' => 'contact@yafahospital.ps',
                'logo' => 'images/hospital_logo/hospital2.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Al-Nasr Hospital',
                'address' => 'Khan Younis, Southern Gaza',
                'phone' => '+970 (8) 205-9999',
                'email' => 'info@alnasrhospital.ps',
                'logo' => 'images/hospital_logo/hospital3.jpg',
                'is_active' => false,
            ],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::create($hospital);
        }

        $this->command->info('Hospitals seeded: ' . count($hospitals));
    }
}