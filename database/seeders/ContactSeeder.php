<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::truncate();
        Contact::create([
            // Basic contact information
            'name' => 'Default Company Contact',
            'email' => 'info@defaultcompany.com',
            'phone' => '+1234567890',
            'alternative_phone' => '+0987654321',

            // Address information
            'address_line1' => '123 Main St',
            'address_line2' => 'Suite 101',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',

            // Social media links
            'facebook' => 'https://facebook.com/defaultcompany',
            'twitter' => 'https://twitter.com/defaultcompany',
            'instagram' => 'https://instagram.com/defaultcompany',
            'linkedin' => 'https://linkedin.com/company/defaultcompany',
            'youtube' => 'https://youtube.com/defaultcompany',
            'whatsapp' => '+1234567890',
            'tiktok' => 'https://tiktok.com/@defaultcompany',
            'snapchat' => 'https://snapchat.com/add/defaultcompany',

            // Other optional fields
            'website' => 'https://defaultcompany.com',
            'google_map_url' => 'https://maps.google.com/?q=123+Main+St,+New+York,+NY',
            'contact_person' => 'John Doe',
            'additional_info' => 'Default contact information for the company.',

            // Status: Active or Disabled
            'is_active' => true,
        ]);
    }
    }
