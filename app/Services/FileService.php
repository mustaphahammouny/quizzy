<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileService
{
    public function put(Model $model, UploadedFile $file, string $collection = null)
    {
        $fileName = Uuid::fromDateTime(Carbon::now()) . '.' . $file->getClientOriginalExtension();
        $media = $model->addMedia($file)
            ->usingFileName($fileName);

        if ($collection) {
            return $media->toMediaCollection($collection);
        }

        return $media->toMediaCollection();
    }

    public function putMany(Model $model, array $files, string $collection = null)
    {
        $medias = [];
        foreach ($files as $file) {
            $medias[] = $this->put($model, $file, $collection);
        }

        return $medias;
    }

    public function get(Model $model, string $collection = null)
    {
        if ($collection) {
            return $model->getMedia($collection);
        }

        return $model->getMedia();
    }

    public function copy()
    {
        // todo
    }

    public function reorder(array $ids)
    {
        Media::setNewOrder($ids);
    }

    public function delete(Media $media)
    {
        return $media->delete();
    }

    public function deleteMany(Collection $medias)
    {
        $deletedMedias = [];
        $medias->each(function ($media) use ($deletedMedias) {
            $deletedMedias[] = $this->delete($media);
        });

        return $deletedMedias;
    }

    public function clearCollection(Model $model, string $collection = null)
    {
        if ($collection) {
            return $model->clearMediaCollection($collection);
        }

        return $model->clearMediaCollection();
    }

    public function generate(Media $media)
    {
        Storage::disk($media->disk)->copy("{$media->id}/{$media->file_name}", "tmp/{$media->file_name}");
        $path = Storage::disk($media->disk)->path("tmp/{$media->file_name}");

        return new UploadedFile($path, $media->file_name, $media->mime_type);
    }

    public function exist(UploadedFile $file, Media $media = null): bool
    {
        return $media?->file_name === $file->getClientOriginalName();
    }
}
