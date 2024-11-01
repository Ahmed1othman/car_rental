<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('feature_translations')->truncate();
        DB::table('features')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Features array with translations in multiple languages
        $features = [
            [
                'is_active' => true,
                'icon_id' => 1, // Example icon ID; adjust based on your icons table
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Air Conditioning',
                        'meta_title' => 'Air Conditioning',
                        'meta_description' => 'Enjoy a cool drive with air conditioning.',
                        'meta_keywords' => 'air conditioning, cool drive',
                        'slug' => 'air-conditioning',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تكييف الهواء',
                        'meta_title' => 'تكييف الهواء',
                        'meta_description' => 'استمتع بتجربة قيادة باردة مع تكييف الهواء.',
                        'meta_keywords' => 'تكييف, هواء, قيادة باردة',
                        'slug' => 'تكييف-الهواء',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Climatisation',
                        'meta_title' => 'Climatisation',
                        'meta_description' => 'Profitez d\'un trajet frais avec la climatisation.',
                        'meta_keywords' => 'climatisation, trajet frais',
                        'slug' => 'climatisation',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Klimaanlage',
                        'meta_title' => 'Klimaanlage',
                        'meta_description' => 'Genießen Sie eine kühle Fahrt mit Klimaanlage.',
                        'meta_keywords' => 'klimaanlage, kühle fahrt',
                        'slug' => 'klimaanlage',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 2,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'GPS Navigation',
                        'meta_title' => 'GPS Navigation',
                        'meta_description' => 'Find your way with built-in GPS navigation.',
                        'meta_keywords' => 'GPS, navigation, map',
                        'slug' => 'gps-navigation',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'نظام الملاحة GPS',
                        'meta_title' => 'نظام الملاحة GPS',
                        'meta_description' => 'اعثر على طريقك باستخدام نظام الملاحة GPS المدمج.',
                        'meta_keywords' => 'نظام ملاحة, GPS, خريطة',
                        'slug' => 'نظام-الملاحة-gps',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Navigation GPS',
                        'meta_title' => 'Navigation GPS',
                        'meta_description' => 'Trouvez votre chemin avec la navigation GPS intégrée.',
                        'meta_keywords' => 'navigation, gps, carte',
                        'slug' => 'navigation-gps',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'GPS Navigation',
                        'meta_title' => 'GPS Navigation',
                        'meta_description' => 'Finden Sie Ihren Weg mit eingebauter GPS-Navigation.',
                        'meta_keywords' => 'GPS, navigation, karte',
                        'slug' => 'gps-navigation-gr',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 3, // Example icon ID for 'Leather Seats'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Leather Seats',
                        'meta_title' => 'Leather Seats',
                        'meta_description' => 'Luxury leather seats for a comfortable drive.',
                        'meta_keywords' => 'leather seats, luxury seats, comfort',
                        'slug' => 'leather-seats',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'مقاعد جلدية',
                        'meta_title' => 'مقاعد جلدية',
                        'meta_description' => 'مقاعد جلدية فاخرة لقيادة مريحة.',
                        'meta_keywords' => 'مقاعد جلدية, فاخرة, مريحة',
                        'slug' => 'مقاعد-جلدية',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Sièges en cuir',
                        'meta_title' => 'Sièges en cuir',
                        'meta_description' => 'Sièges en cuir luxueux pour un confort optimal.',
                        'meta_keywords' => 'sièges en cuir, sièges de luxe, confort',
                        'slug' => 'sieges-en-cuir',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Ledersitze',
                        'meta_title' => 'Ledersitze',
                        'meta_description' => 'Luxuriöse Ledersitze für eine komfortable Fahrt.',
                        'meta_keywords' => 'ledersitze, luxussitze, komfort',
                        'slug' => 'ledersitze',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 4, // Example icon ID for 'Sunroof'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Sunroof',
                        'meta_title' => 'Sunroof',
                        'meta_description' => 'Open the sunroof for a more enjoyable drive.',
                        'meta_keywords' => 'sunroof, open roof, view',
                        'slug' => 'sunroof',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سقف شمس',
                        'meta_title' => 'سقف شمس',
                        'meta_description' => 'افتح السقف للاستمتاع بالقيادة.',
                        'meta_keywords' => 'سقف شمس, سقف مفتوح, رؤية',
                        'slug' => 'سقف-شمس',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Toit ouvrant',
                        'meta_title' => 'Toit ouvrant',
                        'meta_description' => 'Ouvrez le toit pour une conduite plus agréable.',
                        'meta_keywords' => 'toit ouvrant, vue dégagée, plaisir',
                        'slug' => 'toit-ouvrant',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Schiebedach',
                        'meta_title' => 'Schiebedach',
                        'meta_description' => 'Öffnen Sie das Schiebedach für eine schönere Fahrt.',
                        'meta_keywords' => 'schiebedach, offenes dach, ausblick',
                        'slug' => 'schiebedach',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 5, // Example icon ID for 'Bluetooth Audio'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Bluetooth Audio',
                        'meta_title' => 'Bluetooth Audio',
                        'meta_description' => 'Connect your device for hands-free music.',
                        'meta_keywords' => 'bluetooth, audio, hands-free',
                        'slug' => 'bluetooth-audio',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'صوت بلوتوث',
                        'meta_title' => 'صوت بلوتوث',
                        'meta_description' => 'اتصل بجهازك للاستماع بدون استخدام اليدين.',
                        'meta_keywords' => 'بلوتوث, صوت, بدون استخدام اليدين',
                        'slug' => 'صوت-بلوتوث',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Audio Bluetooth',
                        'meta_title' => 'Audio Bluetooth',
                        'meta_description' => 'Connectez votre appareil pour écouter sans les mains.',
                        'meta_keywords' => 'bluetooth, audio, mains-libres',
                        'slug' => 'audio-bluetooth',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Bluetooth-Audio',
                        'meta_title' => 'Bluetooth-Audio',
                        'meta_description' => 'Verbinden Sie Ihr Gerät für freihändiges Hören.',
                        'meta_keywords' => 'bluetooth, audio, freihändig',
                        'slug' => 'bluetooth-audio-fr',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 6, // Example icon ID for 'Parking Sensors'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Parking Sensors',
                        'meta_title' => 'Parking Sensors',
                        'meta_description' => 'Easily navigate tight spaces with parking sensors.',
                        'meta_keywords' => 'parking sensors, assist, navigation',
                        'slug' => 'parking-sensors',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أجهزة استشعار الوقوف',
                        'meta_title' => 'أجهزة استشعار الوقوف',
                        'meta_description' => 'يمكنك التنقل بسهولة في المساحات الضيقة باستخدام أجهزة الاستشعار.',
                        'meta_keywords' => 'استشعار, الوقوف, مساعدة',
                        'slug' => 'أجهزة-استشعار-الوقوف',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Capteurs de stationnement',
                        'meta_title' => 'Capteurs de stationnement',
                        'meta_description' => 'Naviguez facilement dans les espaces restreints avec des capteurs.',
                        'meta_keywords' => 'capteurs de stationnement, aide, navigation',
                        'slug' => 'capteurs-de-stationnement',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Einparksensoren',
                        'meta_title' => 'Einparksensoren',
                        'meta_description' => 'Leichtes Einparken mit Einparksensoren.',
                        'meta_keywords' => 'einparksensoren, hilfe, navigation',
                        'slug' => 'einparksensoren',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 7, // Example icon ID for 'Cruise Control'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Cruise Control',
                        'meta_title' => 'Cruise Control',
                        'meta_description' => 'Automatic speed control for long drives.',
                        'meta_keywords' => 'cruise control, long drive, automatic speed',
                        'slug' => 'cruise-control',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'التحكم بالسرعة',
                        'meta_title' => 'التحكم بالسرعة',
                        'meta_description' => 'التحكم التلقائي في السرعة للرحلات الطويلة.',
                        'meta_keywords' => 'التحكم, سرعة, رحلة طويلة',
                        'slug' => 'التحكم-بالسرعة',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Régulateur de vitesse',
                        'meta_title' => 'Régulateur de vitesse',
                        'meta_description' => 'Contrôle automatique de la vitesse pour de longs trajets.',
                        'meta_keywords' => 'régulateur de vitesse, long trajet, vitesse automatique',
                        'slug' => 'regulateur-de-vitesse',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Tempomat',
                        'meta_title' => 'Tempomat',
                        'meta_description' => 'Automatische Geschwindigkeitsregelung für lange Fahrten.',
                        'meta_keywords' => 'tempomat, lange fahrt, automatische geschwindigkeit',
                        'slug' => 'tempomat',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 8, // Example icon ID for 'Rear Camera'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Rear Camera',
                        'meta_title' => 'Rear Camera',
                        'meta_description' => 'Enhanced visibility when reversing.',
                        'meta_keywords' => 'rear camera, reverse, visibility',
                        'slug' => 'rear-camera',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'كاميرا خلفية',
                        'meta_title' => 'كاميرا خلفية',
                        'meta_description' => 'رؤية محسنة عند الرجوع للخلف.',
                        'meta_keywords' => 'كاميرا خلفية, رجوع, رؤية',
                        'slug' => 'كاميرا-خلفية',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Caméra arrière',
                        'meta_title' => 'Caméra arrière',
                        'meta_description' => 'Visibilité améliorée lors de la marche arrière.',
                        'meta_keywords' => 'caméra arrière, marche arrière, visibilité',
                        'slug' => 'camera-arriere',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Rückfahrkamera',
                        'meta_title' => 'Rückfahrkamera',
                        'meta_description' => 'Verbesserte Sicht beim Rückwärtsfahren.',
                        'meta_keywords' => 'rückfahrkamera, rückwärts, sicht',
                        'slug' => 'ruckfahrkamera',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 9, // Example icon ID for 'Heated Seats'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Heated Seats',
                        'meta_title' => 'Heated Seats',
                        'meta_description' => 'Stay warm with heated seating in cooler weather.',
                        'meta_keywords' => 'heated seats, warmth, comfort',
                        'slug' => 'heated-seats',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'مقاعد مدفأة',
                        'meta_title' => 'مقاعد مدفأة',
                        'meta_description' => 'ابق دافئًا مع المقاعد المدفأة في الطقس البارد.',
                        'meta_keywords' => 'مقاعد, مدفأة, راحة',
                        'slug' => 'مقاعد-مدفأة',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Sièges chauffants',
                        'meta_title' => 'Sièges chauffants',
                        'meta_description' => 'Restez au chaud avec des sièges chauffants par temps frais.',
                        'meta_keywords' => 'sièges chauffants, chaleur, confort',
                        'slug' => 'sieges-chauffants',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Beheizte Sitze',
                        'meta_title' => 'Beheizte Sitze',
                        'meta_description' => 'Bleiben Sie warm mit beheizten Sitzen bei kühlerem Wetter.',
                        'meta_keywords' => 'beheizte sitze, wärme, komfort',
                        'slug' => 'beheizte-sitze',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 10, // Example icon ID for 'Child Seat'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Child Seat',
                        'meta_title' => 'Child Seat',
                        'meta_description' => 'Safety and comfort for young passengers.',
                        'meta_keywords' => 'child seat, safety, comfort',
                        'slug' => 'child-seat',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'مقعد طفل',
                        'meta_title' => 'مقعد طفل',
                        'meta_description' => 'الأمان والراحة للركاب الصغار.',
                        'meta_keywords' => 'مقعد, طفل, أمان, راحة',
                        'slug' => 'مقعد-طفل',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Siège enfant',
                        'meta_title' => 'Siège enfant',
                        'meta_description' => 'Sécurité et confort pour les jeunes passagers.',
                        'meta_keywords' => 'siège enfant, sécurité, confort',
                        'slug' => 'siege-enfant',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Kindersitz',
                        'meta_title' => 'Kindersitz',
                        'meta_description' => 'Sicherheit und Komfort für junge Passagiere.',
                        'meta_keywords' => 'kindersitz, sicherheit, komfort',
                        'slug' => 'kindersitz',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 11, // Example icon ID for 'USB Charging Port'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'USB Charging Port',
                        'meta_title' => 'USB Charging Port',
                        'meta_description' => 'Keep your devices charged on the go.',
                        'meta_keywords' => 'USB charging, power, devices',
                        'slug' => 'usb-charging-port',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'منفذ شحن USB',
                        'meta_title' => 'منفذ شحن USB',
                        'meta_description' => 'حافظ على أجهزتك مشحونة أثناء التنقل.',
                        'meta_keywords' => 'منفذ شحن, USB, طاقة',
                        'slug' => 'منفذ-شحن-usb',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Port de charge USB',
                        'meta_title' => 'Port de charge USB',
                        'meta_description' => 'Gardez vos appareils chargés en déplacement.',
                        'meta_keywords' => 'port de charge, usb, appareils',
                        'slug' => 'port-de-charge-usb',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'USB-Ladeanschluss',
                        'meta_title' => 'USB-Ladeanschluss',
                        'meta_description' => 'Halten Sie Ihre Geräte unterwegs aufgeladen.',
                        'meta_keywords' => 'usb-ladeanschluss, strom, geräte',
                        'slug' => 'usb-ladeanschluss',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'icon_id' => 12, // Example icon ID for 'All-Wheel Drive'
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'All-Wheel Drive',
                        'meta_title' => 'All-Wheel Drive',
                        'meta_description' => 'Enhanced traction and stability on various terrains.',
                        'meta_keywords' => 'all-wheel drive, traction, stability',
                        'slug' => 'all-wheel-drive',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'الدفع الرباعي',
                        'meta_title' => 'الدفع الرباعي',
                        'meta_description' => 'جر وثبات محسّنين على مختلف التضاريس.',
                        'meta_keywords' => 'دفع رباعي, جر, ثبات',
                        'slug' => 'الدفع-الرباعي',
                    ],
                    [
                        'locale' => 'fr',
                        'name' => 'Transmission intégrale',
                        'meta_title' => 'Transmission intégrale',
                        'meta_description' => 'Traction et stabilité améliorées sur divers terrains.',
                        'meta_keywords' => 'transmission intégrale, traction, stabilité',
                        'slug' => 'transmission-integrale',
                    ],
                    [
                        'locale' => 'gr',
                        'name' => 'Allradantrieb',
                        'meta_title' => 'Allradantrieb',
                        'meta_description' => 'Verbesserte Traktion und Stabilität auf verschiedenen Geländen.',
                        'meta_keywords' => 'allradantrieb, traktion, stabilität',
                        'slug' => 'allradantrieb',
                    ],
                ],
            ],
        ];

        // Insert features with translations
        foreach ($features as $feature) {
            $featureId = DB::table('features')->insertGetId([
                'is_active' => $feature['is_active'],
                'icon_id' => $feature['icon_id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($feature['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);

                DB::table('feature_translations')->insert([
                    'feature_id' => $featureId,
                    'locale' => $translation['locale'],
                    'name' => $translation['name'],
                    'meta_title' => $translation['meta_title'],
                    'meta_description' => $translation['meta_description'],
                    'meta_keywords' => json_encode($metaKeywords),
                    'slug' => $translation['slug'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
