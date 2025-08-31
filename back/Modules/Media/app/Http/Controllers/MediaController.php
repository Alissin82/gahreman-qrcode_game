<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function download(Request $request, Media $media)
    {
        $disk = $media->disk;

        if ($disk === 's3') {
            return response()->json([
                'url' => $media->getTemporaryUrl(now()->addMinutes(5))
            ]);
        }

        return response()->download($media->getPath(), $media->file_name, [
            'Content-Type' => $media->mime_type,
        ]);
    }
}
