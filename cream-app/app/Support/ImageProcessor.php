<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use RuntimeException;

/**
 * Re-encodes uploaded images to WebP at two widths (1600 / 800), stripping
 * EXIF (including GPS) in the process, and stores them with random names on
 * the web-served 'uploads' disk. Rejects anything that is not a real
 * JPEG / PNG / WebP by inspecting the file contents, not the extension.
 */
class ImageProcessor
{
    public const MAX_BYTES = 10 * 1024 * 1024; // 10 MB

    public const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];

    protected string $disk = 'uploads';

    protected int $quality = 72;

    public function isValidImage(string $path): bool
    {
        $info = @getimagesize($path);

        return $info !== false && in_array($info['mime'] ?? '', self::ALLOWED_MIME, true);
    }

    /**
     * Re-encode an image to WebP at 1600 and 800px, EXIF stripped.
     *
     * @param  int|null  $maxBytes  Size ceiling; null skips the check (used for
     *                              trusted local imports of our own originals).
     * @return array{path: string, path_sm: string, width: int, height: int}
     *
     * @throws RuntimeException
     */
    public function process(string $sourcePath, string $folder = 'assets', ?int $maxBytes = self::MAX_BYTES): array
    {
        if (! is_file($sourcePath)) {
            throw new RuntimeException('Source image not found.');
        }
        if ($maxBytes !== null && filesize($sourcePath) > $maxBytes) {
            throw new RuntimeException('Image exceeds the '.(int) round($maxBytes / 1048576).' MB limit.');
        }
        if (! $this->isValidImage($sourcePath)) {
            throw new RuntimeException('File is not a valid JPEG, PNG or WebP image.');
        }

        $manager = new ImageManager(new Driver);
        $encoder = new WebpEncoder(quality: $this->quality, strip: true);
        $base = $folder.'/'.now()->format('Y/m').'/'.Str::random(32);

        // 1600px (scaleDown never upscales smaller sources)
        $large = $manager->decodePath($sourcePath)->scaleDown(width: 1600);
        $width = $large->width();
        $height = $large->height();
        $pathLarge = $base.'.webp';
        Storage::disk($this->disk)->put($pathLarge, (string) $large->encode($encoder));

        // 800px variant for srcset
        $small = $manager->decodePath($sourcePath)->scaleDown(width: 800);
        $pathSmall = $base.'-800.webp';
        Storage::disk($this->disk)->put($pathSmall, (string) $small->encode($encoder));

        return [
            'path' => $pathLarge,
            'path_sm' => $pathSmall,
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * @param  array<int, string|null>  $paths
     */
    public function delete(array $paths): void
    {
        Storage::disk($this->disk)->delete(array_values(array_filter($paths)));
    }
}
