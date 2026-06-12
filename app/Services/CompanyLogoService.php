<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class CompanyLogoService
{
    protected string $directory;

    public function __construct()
    {
        $this->directory = storage_path('app/company');
    }

    public function store(UploadedFile $file): string
    {
        $this->ensureDirectoryExists();

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->move($this->directory, $filename);

        return 'company/' . $filename;
    }

    public function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = storage_path('app/' . $path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    public function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return File::exists(storage_path('app/' . $path));
    }

    public function path(string $path): string
    {
        return storage_path('app/' . $path);
    }

    protected function ensureDirectoryExists(): void
    {
        if (!File::exists($this->directory)) {
            File::makeDirectory($this->directory, 0755, true);
        }
    }
}