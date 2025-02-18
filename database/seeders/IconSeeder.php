<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('icons')->truncate();


        $icons = [
            ['name' => 'Manual Transmission', 'icon_class' => 'fa fa-gear'],
            ['name' => 'Bluetooth', 'icon_class' => 'fa fa-bluetooth'],
            ['name' => 'AM/FM Radio', 'icon_class' => 'fa fa-radio'],
            ['name' => 'USB Port', 'icon_class' => 'fa fa-usb'],
            ['name' => 'Air Conditioning', 'icon_class' => 'fa fa-snowflake'],
            ['name' => 'Chart', 'icon_class' => 'fa fa-chart-line'],
            ['name' => 'Battery', 'icon_class' => 'fafa-battery-full'],
            ['name' => 'Shield', 'icon_class' => 'fa fa-shield-alt'],
                ['name' => 'Youtube', 'icon_class' => 'fa-brands fa-youtube'],
            ['name' => 'Automatic Transmission', 'icon_class' => 'fa fa-cogs'],
            ['name' => 'Petrol', 'icon_class' => 'fa fa-gas-pump'],
            ['name' => 'Diesel', 'icon_class' => 'fa fa-oil-can'],
            ['name' => 'Electric', 'icon_class' => 'fa fa-bolt'],
            ['name' => 'Hybrid', 'icon_class' => 'fa fa-charging-station'],
            ['name' => 'GPS Navigation', 'icon_class' => 'fa fa-map-marked-alt'],
            ['name' => 'Heated Seats', 'icon_class' => 'fa fa-fire'],
            ['name' => 'Leather Seats', 'icon_class' => 'fa fa-couch'],
            ['name' => 'Sunroof', 'icon_class' => 'fa fa-sun'],
            ['name' => 'Parking Sensors', 'icon_class' => 'fa fa-car-crash'],
            ['name' => 'Rear Camera', 'icon_class' => 'fa fa-camera'],
            ['name' => 'Keyless Entry', 'icon_class' => 'fa fa-key'],
            ['name' => 'Power Windows', 'icon_class' => 'fa fa-window-maximize'],
            ['name' => 'Cruise Control', 'icon_class' => 'fa fa-tachometer-alt'],
            ['name' => '4-Wheel Drive', 'icon_class' => 'fa fa-car'],
            ['name' => 'Alloy Wheels', 'icon_class' => 'fa fa-dot-circle'],
            ['name' => 'Child Seat', 'icon_class' => 'fa fa-child'],
            ['name' => 'Roof Rack', 'icon_class' => 'fa fa-bars'],
            ['name' => 'Tow Hitch', 'icon_class' => 'fa fa-anchor'],
            ['name' => 'Fog Lights', 'icon_class' => 'fa fa-lightbulb'],
            ['name' => 'Arm Rest', 'icon_class' => 'fa fa-hand-rock'],
            ['name' => 'Third-Row Seating', 'icon_class' => 'fa fa-users'],
            ['name' => 'Tinted Windows', 'icon_class' => 'fa fa-adjust'],
            ['name' => 'Anti-lock Braking System', 'icon_class' => 'fa fa-car-crash'],
            ['name' => 'Airbags', 'icon_class' => 'fa fa-shield-alt'],
            ['name' => 'Cup Holders', 'icon_class' => 'fa fa-mug-hot'],
            ['name' => 'Smartphone Integration', 'icon_class' => 'fa fa-mobile-alt'],
            ['name' => 'Hands-Free Calling', 'icon_class' => 'fa fa-phone-alt'],
            ['name' => 'Split-Folding Seats', 'icon_class' => 'fa fa-th'],
            ['name' => 'Blind Spot Monitor', 'icon_class' => 'fa fa-eye'],
            ['name' => 'Lane Departure Warning', 'icon_class' => 'fa fa-road'],
            ['name' => 'Remote Start', 'icon_class' => 'fa fa-play-circle'],
            ['name' => 'Eco Mode', 'icon_class' => 'fa fa-leaf'],
            ['name' => 'Front Collision Warning', 'icon_class' => 'fa fa-exclamation-triangle'],
            ['name' => 'Rear Spoiler', 'icon_class' => 'fa fa-spa'],
            ['name' => 'WiFi', 'icon_class' => 'fa fa-wifi'],
            ['name' => 'Steering Wheel Controls', 'icon_class' => 'fa fa-hands-helping'],
            ['name' => 'Underbody Protection', 'icon_class' => 'fa fa-shield-alt'],
            ['name' => 'Emergency Kit', 'icon_class' => 'fa fa-first-aid'],
            ['name' => 'Passenger Airbags', 'icon_class' => 'fa fa-user-shield'],
            ['name' => 'Driver Assistance', 'icon_class' => 'fa fa-life-ring'],
            ['name' => 'Home', 'icon_class' => 'fa fa-home'],
            ['name' => 'User', 'icon_class' => 'fa fa-user'],
            ['name' => 'Cog', 'icon_class' => 'fa fa-cog'],
            ['name' => 'Envelope', 'icon_class' => 'fa fa-envelope'],
            ['name' => 'Bell', 'icon_class' => 'fa fa-bell'],
            ['name' => 'Camera', 'icon_class' => 'fa fa-camera'],
            ['name' => 'Heart', 'icon_class' => 'fa fa-heart'],
            ['name' => 'Car', 'icon_class' => 'fa fa-car'],
            ['name' => 'Shopping Cart', 'icon_class' => 'fa fa-shopping-cart'],
            ['name' => 'Trash', 'icon_class' => 'fa fa-trash'],
            ['name' => 'Download', 'icon_class' => 'fa fa-download'],
            ['name' => 'Edit', 'icon_class' => 'fa fa-edit'],
            ['name' => 'File', 'icon_class' => 'fa fa-file'],
            ['name' => 'Info', 'icon_class' => 'fa fa-info'],
            ['name' => 'Lock', 'icon_class' => 'fa fa-lock'],
            ['name' => 'Map Marker', 'icon_class' => 'fa fa-map-marker'],
            ['name' => 'Microphone', 'icon_class' => 'fa fa-microphone'],
            ['name' => 'Mobile', 'icon_class' => 'fa fa-mobile'],
            ['name' => 'Music', 'icon_class' => 'fa fa-music'],
            ['name' => 'Paperclip', 'icon_class' => 'fa fa-paperclip'],
            ['name' => 'Phone', 'icon_class' => 'fa fa-phone'],
            ['name' => 'Printer', 'icon_class' => 'fa fa-print'],
            ['name' => 'Save', 'icon_class' => 'fa fa-save'],
            ['name' => 'Search', 'icon_class' => 'fa fa-search'],
            ['name' => 'Star', 'icon_class' => 'fa fa-star'],
            ['name' => 'Tag', 'icon_class' => 'fa fa-tag'],
            ['name' => 'Thumbs Up', 'icon_class' => 'fa fa-thumbs-up'],
            ['name' => 'Times', 'icon_class' => 'fa fa-times'],
            ['name' => 'Upload', 'icon_class' => 'fa fa-upload'],
            ['name' => 'Wrench', 'icon_class' => 'fa fa-wrench'],
            ['name' => 'Clock', 'icon_class' => 'fa fa-clock'],
            ['name' => 'Calendar', 'icon_class' => 'fa fa-calendar'],
            ['name' => 'Cloud', 'icon_class' => 'fa fa-cloud'],
            ['name' => 'Credit Card', 'icon_class' => 'fa fa-credit-card'],
            ['name' => 'Database', 'icon_class' => 'fa fa-database'],
            ['name' => 'Desktop', 'icon_class' => 'fa fa-desktop'],
            ['name' => 'Dollar Sign', 'icon_class' => 'fa fa-dollar-sign'],
            ['name' => 'Exclamation', 'icon_class' => 'fa fa-exclamation'],
            ['name' => 'Globe', 'icon_class' => 'fa fa-globe'],
            ['name' => 'HDD', 'icon_class' => 'fa fa-hdd'],
            ['name' => 'Key', 'icon_class' => 'fa fa-key'],
            ['name' => 'Lightbulb', 'icon_class' => 'fa fa-lightbulb'],
            ['name' => 'Location Arrow', 'icon_class' => 'fa fa-location-arrow'],
            ['name' => 'Mail Bulk', 'icon_class' => 'fa fa-mail-bulk'],
            ['name' => 'Map', 'icon_class' => 'fa fa-map'],
            ['name' => 'Phone Square', 'icon_class' => 'fa fa-phone-square'],
            ['name' => 'Plug', 'icon_class' => 'fa fa-plug'],
            ['name' => 'Rocket', 'icon_class' => 'fa fa-rocket'],
            ['name' => 'Shopping Bag', 'icon_class' => 'fa fa-shopping-bag'],
            ['name' => 'Tablet', 'icon_class' => 'fa fa-tablet'],
            ['name' => 'Taxi', 'icon_class' => 'fa fa-taxi'],
            ['name' => 'Thermometer', 'icon_class' => 'fa fa-thermometer'],
            ['name' => 'Tools', 'icon_class' => 'fa fa-tools'],
            ['name' => 'University', 'icon_class' => 'fa fa-university'],
            ['name' => 'User Circle', 'icon_class' => 'fa fa-user-circle'],
            ['name' => 'Video', 'icon_class' => 'fa fa-video'],
            ['name' => 'Wifi', 'icon_class' => 'fa fa-wifi'],
            ['name' => 'Wine Glass', 'icon_class' => 'fa fa-wine-glass'],

        ];

        DB::table('icons')->insert($icons);
    }
}
