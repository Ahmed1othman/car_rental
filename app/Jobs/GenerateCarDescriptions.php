<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Car;
use App\Models\CarTranslation;

class GenerateCarDescriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $car;
    protected $locale;

    public function __construct(Car $car, string $locale)
    {
        $this->car = $car;
        $this->locale = $locale;
    }

    public function handle()
    {
        $car = $this->car;
        $translation = $car->translations()->where('locale', $this->locale)->first();

        if (!$translation) {
            return;
        }

        // Generate short description only if it's empty or null
        if (empty($translation->description)) {
            $this->generateShortDescription($translation);
        }

        // Generate long description only if it's empty or null
        if (empty($translation->long_description)) {
            $this->generateLongDescription($translation);
        }

        // Only save if either description was generated
        if ($translation->isDirty('description') || $translation->isDirty('long_description')) {
            $translation->save();
        }
    }

    protected function generateShortDescription(CarTranslation $translation)
    {
        $car = $this->car;
        $name = $translation->name;
        $color = $car->color ? $car->color->translations()->where('locale', $this->locale)->first()?->name : '';
        $year = $car->year?->year ?? '';
        $model = $car->carModel ? $car->carModel->translations()->where('locale', $this->locale)->first()?->name : '';
        $brand = $car->brand ? $car->brand->translations()->where('locale', $this->locale)->first()?->name : '';

        $description = match($this->locale) {
            'ar' => "اكتشف الفخامة والأناقة مع {$brand} {$name} موديل {$year} باللون {$color} الفاخر" . ($model ? " طراز {$model}" : "") . " في دبي. سيارة استثنائية تجمع بين الأداء المتميز والراحة المطلقة، متوفرة الآن للإيجار مع خدمة توصيل مجانية.",
            'fr' => "Découvrez l'élégance et le luxe de la {$brand} {$name} {$year}" . ($color ? " en {$color}" : "") . ($model ? ", modèle {$model}" : "") . " à Dubaï. Une expérience de conduite exceptionnelle avec livraison gratuite incluse.",
            'de' => "Entdecken Sie den Luxus und die Eleganz des {$brand} {$name} {$year}" . ($color ? " in {$color}" : "") . ($model ? ", Modell {$model}" : "") . " in Dubai. Ein außergewöhnliches Fahrerlebnis mit kostenlosem Lieferservice.",
            'es' => "Descubra la elegancia y el lujo del {$brand} {$name} {$year}" . ($color ? " en color {$color}" : "") . ($model ? ", modelo {$model}" : "") . " en Dubái. Una experiencia de conducción excepcional con entrega gratuita incluida.",
            'tr' => "Dubai'de {$year} model {$brand} {$name}" . ($color ? " {$color}" : "") . ($model ? " {$model}" : "") . " ile lüks ve zarafeti keşfedin. Ücretsiz teslimat ile birlikte olağanüstü bir sürüş deneyimi.",
            default => "Discover luxury and sophistication with the {$year} {$brand} {$name}" . ($color ? " in stunning {$color}" : "") . ($model ? ", {$model} model" : "") . " in Dubai. Experience exceptional performance and comfort, now available for rent with complimentary delivery service."
        };

        $translation->description = $description;
    }

    protected function generateLongDescription(CarTranslation $translation)
    {
        $car = $this->car;
        $name = $translation->name;
        $color = $car->color ? $car->color->translations()->where('locale', $this->locale)->first()?->name : '';
        $year = $car->year?->year ?? '';
        $model = $car->carModel ? $car->carModel->translations()->where('locale', $this->locale)->first()?->name : '';
        $brand = $car->brand ? $car->brand->translations()->where('locale', $this->locale)->first()?->name : '';
        
        $features = $car->features()
            ->with(['translations' => function($q) {
                $q->where('locale', $this->locale);
            }])
            ->get()
            ->pluck('translations.*.name')
            ->flatten()
            ->filter()
            ->toArray();

        $titles = [
            'ar' => [
                'title' => "استئجار {$brand} {$name} {$year} في دبي - أفضل عروض تأجير السيارات الفاخرة",
                'intro' => "اكتشف قمة الفخامة والراحة مع {$brand} {$name} موديل {$year} باللون {$color}" . ($model ? " طراز {$model}" : "") . ". سيارة مثالية للتنقل في دبي ودولة الإمارات العربية المتحدة، تجمع بين الأداء العالي والرفاهية المطلقة. مع خدمة عملاء متميزة على مدار الساعة وأسعار تنافسية، نضمن لك تجربة قيادة لا تُنسى.",
                'features' => "المميزات الحصرية",
                'specs' => "المواصفات التقنية",
                'doors' => "عدد الأبواب",
                'luggage' => "سعة الأمتعة",
                'passengers' => "سعة الركاب",
                'rental_benefits' => "مزايا التأجير",
                'popular_locations' => "مواقع شهيرة للزيارة",
                'why_choose' => "لماذا تختار سيارتنا؟",
                'locations' => [
                    "برج خليفة ودبي مول",
                    "نخلة جميرا",
                    "برج العرب",
                    "دبي مارينا",
                    "المدينة القديمة",
                    "مرسى دبي"
                ],
                'benefits' => [
                    "خدمة توصيل السيارة مجاناً في دبي",
                    "تأمين شامل يغطي جميع الحالات",
                    "دعم فني متخصص على مدار الساعة",
                    "صيانة دورية مجانية",
                    "كيلومترات غير محدودة للرحلات الطويلة",
                    "خدمة العملاء بلغات متعددة",
                    "أسعار تنافسية وشفافة",
                    "إجراءات حجز سريعة وسهلة"
                ],
                'why_points' => [
                    "صيانة دورية منتظمة",
                    "نظافة وتعقيم مستمر",
                    "موديل حديث وحالة ممتازة",
                    "تجهيزات داخلية فاخرة",
                    "أنظمة سلامة متطورة"
                ],
                'footer' => "احجز سيارتك الآن واستمتع بتجربة قيادة لا تُنسى في دبي. نحن نضمن لك أفضل خدمة تأجير سيارات في الإمارات."
            ],
            'fr' => [
                'title' => "Location de {$brand} {$name} {$year} à Dubaï - Services de Location de Voitures Premium",
                'intro' => "Découvrez un luxe et un confort inégalés avec la {$brand} {$name} {$year}" . ($color ? " en {$color}" : "") . ($model ? ", modèle {$model}" : "") . ". Parfaite pour Dubaï et les Émirats Arabes Unis, ce véhicule premium allie performance exceptionnelle et élégance sophistiquée. Avec notre service clientèle 24/7 et nos tarifs compétitifs, nous vous garantissons une expérience de conduite inoubliable.",
                'features' => "Caractéristiques Exclusives",
                'specs' => "Spécifications Techniques",
                'doors' => "Nombre de portes",
                'luggage' => "Capacité de bagages",
                'passengers' => "Capacité de passagers",
                'rental_benefits' => "Avantages Premium",
                'popular_locations' => "Destinations Populaires",
                'why_choose' => "Pourquoi Choisir Notre Véhicule?",
                'locations' => [
                    "Burj Khalifa & Dubai Mall",
                    "Palm Jumeirah",
                    "Burj Al Arab",
                    "Dubai Marina",
                    "Vieux Dubai",
                    "Dubai Creek"
                ],
                'benefits' => [
                    "Livraison gratuite à Dubaï",
                    "Assurance tous risques",
                    "Support technique dédié 24/7",
                    "Entretien régulier inclus",
                    "Kilométrage illimité",
                    "Service client multilingue",
                    "Prix compétitifs transparents",
                    "Processus de réservation rapide"
                ],
                'why_points' => [
                    "Programme d'entretien régulier",
                    "Nettoyage et désinfection minutieux",
                    "Dernier modèle en excellent état",
                    "Équipements intérieurs premium",
                    "Systèmes de sécurité avancés"
                ],
                'footer' => "Réservez votre location de voiture premium maintenant et vivez le summum du plaisir de conduire à Dubaï. Nous garantissons le meilleur service de location de voitures aux EAU."
            ],
            'de' => [
                'title' => "{$brand} {$name} {$year} Mieten in Dubai - Premium Autovermietung",
                'intro' => "Erleben Sie unvergleichlichen Luxus und Komfort mit dem {$brand} {$name} {$year}" . ($color ? " in {$color}" : "") . ($model ? ", Modell {$model}" : "") . ". Perfekt für Dubai und die VAE, vereint dieses Premium-Fahrzeug außergewöhnliche Leistung mit sophistizierter Eleganz. Mit unserem 24/7 Kundenservice und wettbewerbsfähigen Preisen garantieren wir ein unvergessliches Fahrerlebnis.",
                'features' => "Exklusive Ausstattung",
                'specs' => "Technische Daten",
                'doors' => "Anzahl der Türen",
                'luggage' => "Gepäckkapazität",
                'passengers' => "Passagierkapazität",
                'rental_benefits' => "Premium-Mietvorteile",
                'popular_locations' => "Beliebte Reiseziele",
                'why_choose' => "Warum Unser Fahrzeug Wählen?",
                'locations' => [
                    "Burj Khalifa & Dubai Mall",
                    "Palm Jumeirah",
                    "Burj Al Arab",
                    "Dubai Marina",
                    "Alt-Dubai",
                    "Dubai Creek"
                ],
                'benefits' => [
                    "Kostenlose Lieferung in Dubai",
                    "Umfassender Versicherungsschutz",
                    "Technischer Support rund um die Uhr",
                    "Regelmäßige Wartung inklusive",
                    "Unbegrenzte Kilometerzahl",
                    "Mehrsprachiger Kundenservice",
                    "Transparente Preisgestaltung",
                    "Schneller Buchungsprozess"
                ],
                'why_points' => [
                    "Regelmäßiger Wartungsplan",
                    "Gründliche Reinigung und Desinfektion",
                    "Neuestes Modell in ausgezeichnetem Zustand",
                    "Premium-Innenausstattung",
                    "Fortschrittliche Sicherheitssysteme"
                ],
                'footer' => "Buchen Sie jetzt Ihre Premium-Autovermietung und erleben Sie höchsten Fahrgenuss in Dubai. Wir garantieren den besten Mietwagenservice in den VAE."
            ],
            'es' => [
                'title' => "Alquiler de {$brand} {$name} {$year} en Dubái - Servicios Premium de Alquiler de Coches",
                'intro' => "Experimente un lujo y confort sin igual con el {$brand} {$name} {$year}" . ($color ? " en color {$color}" : "") . ($model ? ", modelo {$model}" : "") . ". Perfecto para Dubái y los EAU, este vehículo premium combina un rendimiento excepcional con una elegancia sofisticada. Con nuestro servicio al cliente 24/7 y tarifas competitivas, le garantizamos una experiencia de conducción inolvidable.",
                'features' => "Características Exclusivas",
                'specs' => "Especificaciones Técnicas",
                'doors' => "Número de puertas",
                'luggage' => "Capacidad de equipaje",
                'passengers' => "Capacidad de pasajeros",
                'rental_benefits' => "Beneficios Premium del Alquiler",
                'popular_locations' => "Destinos Populares",
                'why_choose' => "¿Por Qué Elegir Nuestro Vehículo?",
                'locations' => [
                    "Burj Khalifa y Dubai Mall",
                    "Palm Jumeirah",
                    "Burj Al Arab",
                    "Dubai Marina",
                    "Dubái Antiguo",
                    "Dubai Creek"
                ],
                'benefits' => [
                    "Entrega gratuita en Dubái",
                    "Seguro integral",
                    "Soporte técnico 24/7",
                    "Mantenimiento regular incluido",
                    "Kilometraje ilimitado",
                    "Servicio al cliente multilingüe",
                    "Precios competitivos transparentes",
                    "Proceso de reserva rápido"
                ],
                'why_points' => [
                    "Programa de mantenimiento regular",
                    "Limpieza y desinfección minuciosa",
                    "Último modelo en excelente estado",
                    "Características interiores premium",
                    "Sistemas de seguridad avanzados"
                ],
                'footer' => "Reserve ahora su alquiler de coche premium y experimente el máximo placer de conducir en Dubái. Garantizamos el mejor servicio de alquiler de coches en los EAU."
            ],
            'tr' => [
                'title' => "Dubai'de {$brand} {$name} {$year} Kiralama - Premium Araç Kiralama Hizmetleri",
                'intro' => "{$year} model {$brand} {$name}" . ($color ? " {$color}" : "") . ($model ? " {$model}" : "") . " ile eşsiz lüks ve konforu keşfedin. Dubai ve BAE için mükemmel olan bu premium araç, olağanüstü performansı zarif tasarımla birleştiriyor. 7/24 müşteri hizmetlerimiz ve rekabetçi fiyatlarımızla unutulmaz bir sürüş deneyimi garantiliyoruz.",
                'features' => "Özel Özellikler",
                'specs' => "Teknik Özellikler",
                'doors' => "Kapı sayısı",
                'luggage' => "Bagaj kapasitesi",
                'passengers' => "Yolcu kapasitesi",
                'rental_benefits' => "Premium Kiralama Avantajları",
                'popular_locations' => "Popüler Destinasyonlar",
                'why_choose' => "Neden Bizim Aracımızı Seçmelisiniz?",
                'locations' => [
                    "Burj Khalifa ve Dubai Mall",
                    "Palm Jumeirah",
                    "Burj Al Arab",
                    "Dubai Marina",
                    "Eski Dubai",
                    "Dubai Creek"
                ],
                'benefits' => [
                    "Dubai'de ücretsiz teslimat",
                    "Kapsamlı sigorta",
                    "7/24 teknik destek",
                    "Düzenli bakım dahil",
                    "Sınırsız kilometre",
                    "Çok dilli müşteri hizmeti",
                    "Şeffaf rekabetçi fiyatlandırma",
                    "Hızlı rezervasyon süreci"
                ],
                'why_points' => [
                    "Düzenli bakım programı",
                    "Detaylı temizlik ve dezenfeksiyon",
                    "Mükemmel durumda son model",
                    "Premium iç özellikler",
                    "Gelişmiş güvenlik sistemleri"
                ],
                'footer' => "Premium araç kiralamanızı şimdi yapın ve Dubai'de en üst düzey sürüş keyfini yaşayın. BAE'de en iyi araç kiralama hizmetini garanti ediyoruz."
            ],
            'default' => [
                'title' => "Rent {$brand} {$name} {$year} in Dubai - Premium Car Rental Services",
                'intro' => "Experience unparalleled luxury and comfort with the {$year} {$brand} {$name}" . ($color ? " in elegant {$color}" : "") . ($model ? ", {$model} model" : "") . ". Perfect for Dubai and the UAE, this premium vehicle combines exceptional performance with sophisticated elegance. With our 24/7 customer service and competitive rates, we ensure an unforgettable driving experience.",
                'features' => "Exclusive Features",
                'specs' => "Technical Specifications",
                'doors' => "Number of doors",
                'luggage' => "Luggage capacity",
                'passengers' => "Passenger capacity",
                'rental_benefits' => "Premium Rental Benefits",
                'popular_locations' => "Popular Destinations",
                'why_choose' => "Why Choose Our Vehicle?",
                'locations' => [
                    "Burj Khalifa & Dubai Mall",
                    "Palm Jumeirah",
                    "Burj Al Arab",
                    "Dubai Marina",
                    "Old Dubai",
                    "Dubai Creek"
                ],
                'benefits' => [
                    "Complimentary delivery across Dubai",
                    "Comprehensive insurance coverage",
                    "Dedicated 24/7 technical support",
                    "Regular maintenance included",
                    "Unlimited mileage for extended trips",
                    "Multilingual customer service",
                    "Transparent competitive pricing",
                    "Swift and easy booking process"
                ],
                'why_points' => [
                    "Regular maintenance schedule",
                    "Thorough cleaning and sanitization",
                    "Latest model in excellent condition",
                    "Premium interior features",
                    "Advanced safety systems"
                ],
                'footer' => "Book your premium car rental now and experience the ultimate driving pleasure in Dubai. We guarantee the finest car rental service in the UAE."
            ]
        ];

        $t = $titles[$this->locale] ?? $titles['default'];

        $html = "<article class='car-description' itemscope itemtype='https://schema.org/Car'>";
        $html .= "<h1 itemprop='name'>{$t['title']}</h1>";
        $html .= "<div class='car-intro'><p itemprop='description'>{$t['intro']}</p></div>";

        if (!empty($features)) {
            $html .= "<section class='car-features'>";
            $html .= "<h2>{$t['features']}</h2>";
            $html .= "<ul class='feature-list' itemprop='vehicleSpecialUsage'>";
            foreach ($features as $feature) {
                $html .= "<li>{$feature}</li>";
            }
            $html .= "</ul>";
            $html .= "</section>";
        }

        $html .= "<section class='car-specs'>";
        $html .= "<h2>{$t['specs']}</h2>";
        $html .= "<ul class='specs-list'>";
        if ($car->door_count) {
            $html .= "<li><strong>{$t['doors']}:</strong> <span itemprop='numberOfDoors'>{$car->door_count}</span></li>";
        }
        if ($car->luggage_capacity) {
            $html .= "<li><strong>{$t['luggage']}:</strong> <span itemprop='cargoVolume'>{$car->luggage_capacity}</span></li>";
        }
        if ($car->passenger_capacity) {
            $html .= "<li><strong>{$t['passengers']}:</strong> <span itemprop='seatingCapacity'>{$car->passenger_capacity}</span></li>";
        }
        $html .= "</ul>";
        $html .= "</section>";

        $html .= "<section class='rental-benefits'>";
        $html .= "<h2>{$t['rental_benefits']}</h2>";
        $html .= "<ul class='benefits-list'>";
        foreach ($t['benefits'] as $benefit) {
            $html .= "<li>{$benefit}</li>";
        }
        $html .= "</ul>";
        $html .= "</section>";

        $html .= "<section class='why-choose'>";
        $html .= "<h2>{$t['why_choose']}</h2>";
        $html .= "<ul class='why-points'>";
        foreach ($t['why_points'] as $point) {
            $html .= "<li>{$point}</li>";
        }
        $html .= "</ul>";
        $html .= "</section>";

        $html .= "<section class='popular-locations'>";
        $html .= "<h2>{$t['popular_locations']}</h2>";
        $html .= "<ul class='locations-list'>";
        foreach ($t['locations'] as $location) {
            $html .= "<li>{$location}</li>";
        }
        $html .= "</ul>";
        $html .= "</section>";

        $html .= "<footer class='car-footer'>";
        $html .= "<p>{$t['footer']}</p>";
        $html .= "</footer>";

        // Add Schema.org JSON-LD
        $html .= "<script type='application/ld+json'>";
        $html .= json_encode([
            "@context" => "https://schema.org",
            "@type" => "Car",
            "name" => "{$brand} {$name}",
            "manufacturer" => $brand,
            "model" => $model,
            "modelDate" => $year,
            "color" => $color,
            "numberOfDoors" => $car->door_count,
            "seatingCapacity" => $car->passenger_capacity,
            "description" => strip_tags($t['intro'])
        ]);
        $html .= "</script>";
        
        $html .= "</article>";

        if ($this->locale === 'ar') {
            $html = $this->formatArabicDescription($html);
        } else {
            $html = $this->formatEnglishDescription($html);
        }

        $translation->long_description = $html;
    }

    protected function formatArabicDescription($description)
    {
        $sections = explode("\n\n", trim($description));
        $formattedSections = [];
        
        foreach ($sections as $index => $section) {
            if ($index === 0) {
                // First section is the main title
                $formattedSections[] = "<h1>{$section}</h1>";
            } elseif (strpos($section, ':') !== false || strlen($section) < 50) {
                // Likely a section heading
                $section = str_replace(':', '', $section);
                $formattedSections[] = "<h2>{$section}</h2>";
            } else {
                // Regular content
                if (strpos($section, '•') !== false || strpos($section, '-') !== false) {
                    // Convert bullet points to proper HTML list
                    $items = array_map('trim', explode("\n", $section));
                    $listItems = array_map(function($item) {
                        return "<li>" . trim(str_replace(['•', '-'], '', $item)) . "</li>";
                    }, $items);
                    $formattedSections[] = "<ul>" . implode("\n", $listItems) . "</ul>";
                } else {
                    $formattedSections[] = "<p>{$section}</p>";
                }
            }
        }

        return "<div dir='rtl'>" . implode("\n\n", $formattedSections) . "</div>";
    }

    protected function formatEnglishDescription($description)
    {
        $sections = explode("\n\n", trim($description));
        $formattedSections = [];
        
        foreach ($sections as $index => $section) {
            if ($index === 0) {
                // First section is the main title
                $formattedSections[] = "<h1>{$section}</h1>";
            } elseif (strpos($section, ':') !== false || strlen($section) < 50) {
                // Likely a section heading
                $section = str_replace(':', '', $section);
                $formattedSections[] = "<h2>{$section}</h2>";
            } else {
                // Regular content
                if (strpos($section, '•') !== false || strpos($section, '-') !== false) {
                    // Convert bullet points to proper HTML list
                    $items = array_map('trim', explode("\n", $section));
                    $listItems = array_map(function($item) {
                        return "<li>" . trim(str_replace(['•', '-'], '', $item)) . "</li>";
                    }, $items);
                    $formattedSections[] = "<ul>" . implode("\n", $listItems) . "</ul>";
                } else {
                    $formattedSections[] = "<p>{$section}</p>";
                }
            }
        }

        return "<div>" . implode("\n\n", $formattedSections) . "</div>";
    }
}
