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
            'google_map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d109180.09413296978!2d29.954885900000004!3d31.224110850000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14f5c49126710fd3%3A0xb4e0cda629ee6bb9!2sAlexandria%2C%20Alexandria%20Governorate!5e0!3m2!1sen!2seg!4v1732324196248!5m2!1sen!2seg',
            'contact_person' => 'John Doe',
            'additional_info' => 'Default contact information for the company.',

            // Status: Active or Disabled
            'is_active' => true,
        ]);
    }
    }
