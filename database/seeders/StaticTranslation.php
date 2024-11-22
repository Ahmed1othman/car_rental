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

            // Get the locale from the request or use a default
            $locale = $request->header('Accept-Language', 'en');

            // Process the data to convert numbers and handle pluralization
            $data = $this->processData($data, $locale);

            // Set the modified data back to the response
            $response->setData($data);
        }

        return $response;
    }

    /**
     * Process data for number conversion and pluralization.
     *
     * @param  mixed  $data
     * @param  string  $locale
     * @return mixed
     */
    protected function processData($data, $locale)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Skip specific fields
                if (in_array($key, ['file_path', 'default_image_path', 'hero_header_video_path', 'slug', 'hero_header_image_path'], true) ||
                    filter_var($value, FILTER_VALIDATE_URL)) {
                    continue;
                }

                if ($key === 'car_count') {
                    $data[$key] = $this->formatCarCount($value, $locale);
                } else {
                    $data[$key] = $this->processData($value, $locale);
                }
            }
        } elseif (is_numeric($data) && $locale === 'ar') {
            // Convert numbers to Arabic digits
            $data = $this->convertToArabicNumbers($data);
        }

        return $data;
    }

    /**
     * Format car count based on the locale.
     *
     * @param  string|int  $count
     * @param  string  $locale
     * @return string
     */
    protected function formatCarCount($count, $locale)
    {
        // Extract numeric count
        $numericCount = (int) filter_var($count, FILTER_SANITIZE_NUMBER_INT);

        if ($locale === 'ar') {
            $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            $numericCount = str_replace(range(0, 9), $arabicDigits, $numericCount);
            return $numericCount . ' سيارة' . ($numericCount > 1 ? 'ات' : '');
        } else {
            return $numericCount . ' car' . ($numericCount > 1 ? 's' : '');
        }
    }

    /**
     * Convert numbers to Arabic digits.
     *
     * @param  int|string  $number
     * @return string
     */
    protected function convertToArabicNumbers($number)
    {
        $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str_replace(range(0, 9), $arabicDigits, $number);
    }
}
