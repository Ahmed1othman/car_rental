<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;


class LocationSeeder extends Seeder
{
    protected $translator;

    public function run()
    {

        $locations = [
            [
                'name' => 'Al Barsha',
                'description' => 'A vibrant residential and commercial hub in Dubai, known for its malls and easy accessibility.',
                'content' => '<p>Discover Al Barsha, a thriving area with iconic malls and a central location for shopping and leisure.</p>',
            ],
            [
                'name' => 'Dubai Mall',
                'description' => 'The largest shopping mall in the world, located near Burj Khalifa, offering endless attractions.',
                'content' => '<p>Explore Dubai Mall, a world-renowned destination for shopping, dining, and entertainment near Burj Khalifa.</p>',
            ],
            [
                'name' => 'Business Bay',
                'description' => 'A bustling business district with modern skyscrapers and a vibrant lifestyle.',
                'content' => '<p>Business Bay combines work and leisure with stunning views and top-notch amenities in Dubai.</p>',
            ],
            [
                'name' => 'Jumeirah',
                'description' => 'A picturesque coastal area known for beaches, luxury villas, and tourist attractions.',
                'content' => '<p>Experience the beauty of Jumeirah, featuring pristine beaches and luxurious living in Dubai.</p>',
            ],
            [
                'name' => 'Deira',
                'description' => 'A historical trading hub with vibrant souks and a glimpse of Dubai’s cultural heritage.',
                'content' => '<p>Visit Deira to explore the traditional markets, including the famous Gold and Spice Souks of Dubai.</p>',
            ],
            [
                'name' => 'Al Nahda',
                'description' => 'A family-friendly neighborhood with parks, schools, and convenient amenities.',
                'content' => '<p>Al Nahda offers a peaceful living experience with lush parks and modern conveniences.</p>',
            ],
            [
                'name' => 'Downtown Dubai',
                'description' => 'The heart of Dubai, home to Burj Khalifa and other iconic landmarks.',
                'content' => '<p>Downtown Dubai is where modernity meets luxury, featuring the Burj Khalifa and vibrant city life.</p>',
            ],
            [
                'name' => 'Al Wasl',
                'description' => 'A peaceful residential area offering proximity to city attractions and lush greenery.',
                'content' => '<p>Al Wasl provides a serene escape with easy access to Dubai’s top destinations and green spaces.</p>',
            ],
            [
                'name' => 'Yas Island',
                'description' => 'An entertainment hub in Abu Dhabi featuring world-class attractions like Ferrari World.',
                'content' => '<p>Experience Yas Island, Abu Dhabi\'s top destination for adventure, featuring theme parks and luxury resorts.</p>',
            ],
            [
                'name' => 'Khalifa City',
                'description' => 'A quiet residential area ideal for families, located in Abu Dhabi.',
                'content' => '<p>Khalifa City offers a tranquil environment with excellent facilities for families in Abu Dhabi.</p>',
            ],
            [
                'name' => 'Al Reem Island',
                'description' => 'A modern residential and commercial area in Abu Dhabi with stunning waterfront views.',
                'content' => '<p>Al Reem Island combines luxury living with breathtaking waterfront views and vibrant urban life.</p>',
            ],
            [
                'name' => 'Corniche',
                'description' => 'A scenic promenade in Abu Dhabi with beaches, parks, and recreational areas.',
                'content' => '<p>The Corniche in Abu Dhabi offers stunning views, pristine beaches, and activities for everyone.</p>',
            ],
            [
                'name' => 'Al Falah',
                'description' => 'A family-oriented community in Abu Dhabi with modern infrastructure.',
                'content' => '<p>Al Falah provides a welcoming environment for families with excellent facilities in Abu Dhabi.</p>',
            ],
            [
                'name' => 'Sharjah Corniche',
                'description' => 'A peaceful waterfront in Sharjah, ideal for relaxation and leisure.',
                'content' => '<p>Sharjah Corniche offers stunning views and a tranquil escape by the water for families and visitors alike.</p>',
            ],
            [
                'name' => 'Al Qasimia',
                'description' => 'A bustling neighborhood in Sharjah known for its cultural and shopping experiences.',
                'content' => '<p>Discover Al Qasimia, a lively district filled with shopping, dining, and cultural attractions.</p>',
            ],
            [
                'name' => 'Muwaileh',
                'description' => 'A growing area in Sharjah, popular for its educational institutions and family-friendly vibe.',
                'content' => '<p>Muwaileh is a hub for families, offering top schools and a comfortable living environment in Sharjah.</p>',
            ],
            [
                'name' => 'Ajman Corniche',
                'description' => 'A picturesque coastal area in Ajman with beaches and relaxing vibes.',
                'content' => '<p>Ajman Corniche is the perfect spot to unwind with its serene beaches and scenic beauty.</p>',
            ],
            [
                'name' => 'Al Nuaimiya',
                'description' => 'A vibrant neighborhood in Ajman offering affordable living and local amenities.',
                'content' => '<p>Al Nuaimiya provides a balance of affordability and convenience, making it a popular choice in Ajman.</p>',
            ],
            [
                'name' => 'Umm Al Quwain Corniche',
                'description' => 'A serene coastal promenade offering tranquil views and outdoor activities.',
                'content' => '<p>Umm Al Quwain Corniche is a hidden gem with pristine beaches and a relaxing atmosphere.</p>',
            ],
            [
                'name' => 'Al Rams',
                'description' => 'A small fishing village in Ras Al Khaimah with natural beauty and a calm environment.',
                'content' => '<p>Explore Al Rams, a peaceful village offering a glimpse into Ras Al Khaimah’s traditional lifestyle.</p>',
            ],
            [
                'name' => 'RAK Corniche',
                'description' => 'A scenic waterfront area in Ras Al Khaimah, ideal for family outings and leisure activities.',
                'content' => '<p>RAK Corniche is a family-friendly destination with beautiful views and a range of recreational activities.</p>',
            ],
        ];

        $languages = Language::get();

        foreach ($locations as $location) {
            $newLocation = Location::create(['is_active' => true]);

            foreach ($languages as $language) {
                $translatedData = $this->translateContent($location, $language->code);
                $slug = $this->generateUniqueSlug($translatedData['name'], $language->code);

                $newLocation->translations()->create([
                    'locale' => $language->code,
                    'name' => $translatedData['name'],
                    'slug' => $slug,
                    'description' => $translatedData['description'],
                    'content' => $translatedData['content'],
                ]);
            }
        }
    }

    /**
     * Translate content into the specified locale.
     */
    private function translateContent($location, $locale)
    {
        $translatedName = $this->translateText($location['name'], $locale);
        $translatedDescription = $this->translateText($location['description'], $locale);
        $translatedContent = $this->translateText(strip_tags($location['content']), $locale);

        return [
            'name' => $translatedName,
            'description' => $translatedDescription,
            'content' => "<p>" . htmlspecialchars($translatedContent) . "</p>",
        ];
    }

    /**
     * Translate text into the specified locale using GoogleTranslate.
     */
    private function translateText($text, $locale)
    {
        try {
            $translator = new GoogleTranslate($locale);
            return $translator->translate($text);
        } catch (\Exception $e) {
            $this->command->error("Translation failed for '$text': " . $e->getMessage());
            return $text; // Fallback to original text
        }
    }

    /**
     * Generate a unique slug by checking for conflicts and appending random characters if needed.
     */
    private function generateUniqueSlug($name, $locale)
    {
        $slug = Str::slug($name) . '-' . $locale;
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists in the database.
     */
    private function slugExists($slug)
    {
        return \DB::table('location_translations')->where('slug', $slug)->exists();
    }
}
