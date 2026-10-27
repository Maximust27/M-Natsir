<?php

declare(strict_types=1);

/**
 * Create responsive WebP copies of the two existing hero images.
 *
 * Runs automatically before npm run dev/build. Generated files stay out of Git.
 * The Blade templates retain their original image fallback when GD/WebP is unavailable.
 */
$directory = dirname(__DIR__).'/public/images';

$images = [
    'home-hero' => ['source' => 'home-hero.jpeg', 'widths' => [480, 960, 1440]],
    // The About source is PNG, despite previously carrying a .jpeg extension.
    'about-hero' => ['source' => 'about-hero.png', 'widths' => [320, 640]],
];

if (! function_exists('imagecreatefromjpeg')
    || ! function_exists('imagecreatetruecolor')
    || ! function_exists('imagewebp')) {
    fwrite(STDERR, "[hero-images] PHP GD with JPEG/WebP support is not available. Keeping the original JPEG fallback.\n");
    exit(0);
}

$failed = false;

foreach ($images as $name => $image) {
    $widths = $image['widths'];
    $sourcePath = $directory.'/'.$image['source'];

    if (! is_file($sourcePath)) {
        fwrite(STDERR, "[hero-images] Missing source: {$sourcePath}\n");
        $failed = true;

        continue;
    }

    $dimensions = @getimagesize($sourcePath);

    if (! is_array($dimensions)) {
        fwrite(STDERR, "[hero-images] Could not read dimensions: {$sourcePath}\n");
        $failed = true;

        continue;
    }

    $decoder = match ($dimensions[2] ?? null) {
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        default => null,
    };

    if ($decoder === null || ! function_exists($decoder)) {
        fwrite(STDERR, "[hero-images] Unsupported image format: {$sourcePath}\n");
        $failed = true;

        continue;
    }

    [$originalWidth, $originalHeight] = $dimensions;

    // Never upscale the source. Always include a WebP at the native resolution.
    $variants = array_values(array_filter(
        $widths,
        static fn (int $width): bool => $width < $originalWidth,
    ));
    $variants[] = $originalWidth;

    $source = @$decoder($sourcePath);

    if ($source === false) {
        fwrite(STDERR, "[hero-images] Could not decode {$sourcePath}\n");
        $failed = true;

        continue;
    }

    foreach ($variants as $width) {
        $height = (int) max(1, round($originalHeight * $width / $originalWidth));
        $suffix = $width === $originalWidth ? 'full' : (string) $width;
        $destination = $directory.'/'.$name.'-'.$suffix.'.webp';

        // Rebuild when the JPEG or this script changes, but not on every Vite start.
        if (is_file($destination)
            && filemtime($destination) >= max(filemtime($sourcePath), filemtime(__FILE__))) {
            continue;
        }

        $resized = imagecreatetruecolor($width, $height);

        if ($resized === false || ! imagecopyresampled(
            $resized,
            $source,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $originalWidth,
            $originalHeight,
        )) {
            fwrite(STDERR, "[hero-images] Could not resize {$name} at {$width}px\n");
            $failed = true;

            if ($resized !== false) {
                imagedestroy($resized);
            }

            continue;
        }

        $tempFile = $destination.'.tmp';
        $encoded = @imagewebp($resized, $tempFile, 76);
        imagedestroy($resized);

        if (! $encoded || ! is_file($tempFile) || filesize($tempFile) === 0) {
            @unlink($tempFile);
            fwrite(STDERR, "[hero-images] Could not encode {$destination}\n");
            $failed = true;

            continue;
        }

        // Windows (Laragon) can reject renaming over an existing destination.
        if (is_file($destination)) {
            @unlink($destination);
        }

        if (! @rename($tempFile, $destination)) {
            @unlink($tempFile);
            fwrite(STDERR, "[hero-images] Could not save {$destination}\n");
            $failed = true;

            continue;
        }

        $kilobytes = round(filesize($destination) / 1024);
        echo "[hero-images] {$name}: {$width}x{$height} WebP ({$kilobytes} KB)\n";
    }

    imagedestroy($source);
}

exit($failed ? 1 : 0);
