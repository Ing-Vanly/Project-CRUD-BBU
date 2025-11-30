<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HandlesUserImages
{
    protected function handleImageUpload(Request $request, ?string $existingImage = null): ?string
    {
        if (!$request->hasFile('image')) {
            return $existingImage;
        }

        $directory = public_path('uploads/users');
        File::ensureDirectoryExists($directory);

        if ($existingImage) {
            $existingPath = $directory . DIRECTORY_SEPARATOR . $existingImage;
            if (File::exists($existingPath)) {
                File::delete($existingPath);
            }
        }

        $image = $request->file('image');
        $imageName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
        $image->move($directory, $imageName);

        return $imageName;
    }
}
