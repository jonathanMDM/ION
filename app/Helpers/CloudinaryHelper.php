<?php

namespace App\Helpers;

class CloudinaryHelper
{
    protected static function getCloudName()
    {
        return env('CLOUDINARY_CLOUD_NAME', 'dgqphdgmf');
    }

    protected static function getApiKey()
    {
        return env('CLOUDINARY_API_KEY');
    }

    protected static function getApiSecret()
    {
        return env('CLOUDINARY_API_SECRET');
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return array|null
     */
    public static function upload($file, $folder = 'assets')
    {
        try {
            $cloudName = self::getCloudName();
            $apiKey = self::getApiKey();
            $apiSecret = self::getApiSecret();

            if (!$cloudName || !$apiKey || !$apiSecret) {
                \Log::error('Cloudinary credentials not configured');
                return null;
            }

            $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

            // Generate timestamp
            $timestamp = time();

            // Prepare parameters for signature
            $params = [
                'timestamp' => $timestamp,
                'folder' => $folder,
            ];

            // Generate signature
            $signature = self::generateSignature($params, $apiSecret);

            // Prepare the file for upload
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();

            // Create CFile for upload
            $postData = [
                'file' => new \CURLFile($filePath, $file->getMimeType(), $fileName),
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature' => $signature,
            ];

            // Upload using cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                return [
                    'public_id' => $result['public_id'] ?? null,
                    'url' => $result['secure_url'] ?? null,
                    'width' => $result['width'] ?? null,
                    'height' => $result['height'] ?? null,
                    'format' => $result['format'] ?? null,
                ];
            } else {
                \Log::error('Cloudinary upload failed', ['response' => $response, 'code' => $httpCode]);
                return null;
            }

        } catch (\Exception $e) {
            \Log::error('Cloudinary upload exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public static function delete($publicId)
    {
        try {
            $cloudName = self::getCloudName();
            $apiKey = self::getApiKey();
            $apiSecret = self::getApiSecret();

            if (!$cloudName || !$apiKey || !$apiSecret) {
                return false;
            }

            $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy";

            $timestamp = time();
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];

            $signature = self::generateSignature($params, $apiSecret);

            $postData = [
                'public_id' => $publicId,
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;

        } catch (\Exception $e) {
            \Log::error('Cloudinary delete exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Generate Cloudinary signature
     *
     * @param array $params
     * @param string $apiSecret
     * @return string
     */
    protected static function generateSignature($params, $apiSecret)
    {
        ksort($params);
        $signatureString = '';
        foreach ($params as $key => $value) {
            $signatureString .= $key . '=' . $value . '&';
        }
        $signatureString = rtrim($signatureString, '&') . $apiSecret;
        return sha1($signatureString);
    }

    /**
     * Get optimized image URL
     *
     * @param string $publicId
     * @param int $width
     * @param int $height
     * @param string $crop
     * @return string
     */
    public static function getUrl($publicId, $width = null, $height = null, $crop = 'fill')
    {
        $cloudName = self::getCloudName();
        $transformations = [];

        if ($width) {
            $transformations[] = "w_{$width}";
        }
        if ($height) {
            $transformations[] = "h_{$height}";
        }
        if ($width || $height) {
            $transformations[] = "c_{$crop}";
        }

        $transformString = !empty($transformations) ? implode(',', $transformations) . '/' : '';

        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$transformString}{$publicId}";
    }
}
