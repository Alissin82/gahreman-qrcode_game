<?php

namespace Modules\FileUpload\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileUploadTeam extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'file_upload_team';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['team_id', 'file_upload_id'];

    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'attachment');
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }
}
