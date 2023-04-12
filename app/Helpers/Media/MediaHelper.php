<?php

namespace App\Helpers\Media;

use Illuminate\Support\Facades\Storage;


Trait MediaHelper
{

    public function uploadImage($file)
    {
        // Calculate the hash of the file contents
        $hash = hash_file('md5', $file->getRealPath());

        // Check if a file with the same hash already exists in the storage directory
        $existingFile = Storage::disk('public')->allFiles('images');
        foreach ($existingFile as $filePath) {
            if (hash_file('md5', Storage::disk('public')->path($filePath)) === $hash) {
                return url('media/' . $filePath);
            }
        }

        // Generate a unique name for the file
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        // Save the file to the public storage folder
        $path = $file->storeAs('public/images', $fileName);

        // Return the URL of the uploaded file
        return url('media/images/' . $fileName);
    }

}
