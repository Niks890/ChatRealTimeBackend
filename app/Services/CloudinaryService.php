<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CloudinaryService
{
    public function uploadImage($filePath)
    {
        $url = "https://api.cloudinary.com/v1_1/" . env('CLOUDINARY_CLOUD_NAME') . "/image/upload";

        $response = Http::attach(
            'file',
            file_get_contents($filePath),
            'file'
        )->post($url, [
            'upload_preset' => 'ml_default',
        ]);

        // Kiểm tra nếu phản hồi từ Cloudinary không phải là null và chứa dữ liệu
        $responseData = $response->json();

        if ($response->successful() && isset($responseData['public_id'])) {
            $publicId = $responseData['public_id'];
            $imageUrl = "https://res.cloudinary.com/" . env('CLOUDINARY_CLOUD_NAME') . "/image/upload/" . $publicId;

            return [
                'url' => $imageUrl
            ];
        } else {
            // Trường hợp có lỗi trong phản hồi từ Cloudinary
            return [
                'error' => 'Upload failed. Please try again.',
                'details' => $responseData
            ];
        }
    }



    // public function deleteImage($publicId)
    // {
    //     $url = "https://api.cloudinary.com/v1_1/" . env('CLOUDINARY_URL') . "/resources/image/upload/" . $publicId;

    //     $response = Http::post($url, [
    //         'api_key' => 'your_api_key',
    //         'api_secret' => 'your_api_secret',
    //     ]);

    //     return $response->json();
    // }
}
