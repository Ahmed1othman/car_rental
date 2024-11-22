<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ConvertNumbersToArabic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the response is a JSON response
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            // Recursive function to convert numbers
            $data = $this->convertNumbers($data);

            // Set the modified data back to the response
            $response->setData($data);
        }

        return $response;
    }

    /**
     * Convert all numeric values in the array to Arabic, except specific keys.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function convertNumbers($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Skip specific fields
                if (
                    in_array($key, [
                        'file_path',
                        'default_image_path',
                        'hero_header_video_path',
                        'slug',
                        'hero_header_image_path',
                        'social_media_links',
                        'crypto_payment_accepted',
                        'is_flash_sale',
                        'id',
                        'is_featured',
                        'insurance_included',
                        'color_code',
                        'gear_type_id',
                    ], true) ||
                    filter_var($value, FILTER_VALIDATE_URL)
                ) {
                    continue;
                }

                $data[$key] = $this->convertNumbers($value);
            }
        } elseif (is_string($data)) {
            // Special handling for strings like "21 سيارة"
            $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            $data = preg_replace_callback('/\d+/', function($matches) use ($arabicDigits) {
                return str_replace(range(0, 9), $arabicDigits, $matches[0]);
            }, $data);
        } elseif (is_numeric($data)) {
            // Convert pure numeric values to Arabic
            $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            $data = str_replace(range(0, 9), $arabicDigits, $data);
        }

        return $data;
    }
}
