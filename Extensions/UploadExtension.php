<?php

namespace App\Component\Extensions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait UploadExtension
{
    /**
     * Upload $data file in given $disk in given $dir
     *
     * @param string $dir
     * @param UploadedFile $data
     * @param string $name
     * @param string $disk
     * @param bool $throwError
     * @return string|null
     * @throws ValidationException
     */
    protected function upload($dir, $data, $name, $disk = 'public_uploads', $throwError = false)
    {
        $fileName = "{$name}.{$data->getClientOriginalExtension()}";
        $filePath = $this->getFileName($dir, $fileName, $disk);
        if (!$this->deleteFile($filePath, $disk, $throwError)) {
            return null;
        }
        if (!Storage::disk($disk)->putFileAs($dir, $data, $fileName)) {
            if ($throwError) {
                throw new ValidationException(null, $this->badRequestMsg('مشکل در آپلود فایل.'));
            }
            return null;
        }
        return $filePath;
    }

    /**
     * @param string $dir
     * @param string $fileName
     * @param string $disk
     * @return string
     */
    protected function getFileName($dir, $fileName, $disk)
    {
        if ($disk == 'public') {
            if (starts_with($dir, '/')) {
                $dir = "storage$dir";
            } else {
                $dir = "storage/$dir";
            }
        }
        if (ends_with($dir, '/')) {
            return "/{$dir}{$fileName}";
        } else {
            return "/{$dir}/{$fileName}";
        }
    }

    /**
     * @param string $filePath
     * @param string $disk
     * @param bool $throwError
     * @return null
     * @throws ValidationException
     */
    protected function deleteFile($filePath, $disk = 'public_uploads', $throwError = false)
    {
        if (Storage::disk($disk)->exists($filePath)) {
            if (!Storage::disk($disk)->delete($filePath)) {
                if ($throwError) {
                    throw new ValidationException(null, $this->badRequestMsg('مشکل در حذف فایل.'));
                }
                return false;
            }
            return true;
        }
        return true;
    }
}