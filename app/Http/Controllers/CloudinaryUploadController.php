<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class CloudinaryUploadController extends Controller
{
    protected $cloudinaryService;


    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function showForm()
    {
        return view('welcome');
    }

    // public function upload(Request $request)
    // {
    //     $file = $request->file('image');

    //     $uploadResponse = $this->cloudinaryService->uploadImage($file->getPathname());

    //     return response()->json($uploadResponse);
    // }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|max:2048', // Kiểm tra file ảnh có hợp lệ không
        ]);

        $file = $request->file('image');
        $uploadResponse = $this->cloudinaryService->uploadImage($file->getPathname());

        return response()->json($uploadResponse);
    }
}
