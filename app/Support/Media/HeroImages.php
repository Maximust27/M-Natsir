<?php

namespace App\Support\Media;

final class HeroImages
{
    private const WIDTHS = [
        'home-hero' => [480, 960, 1440],
        'about-hero' => [320, 640],
    ];

    /**
     * Return only WebP files that actually exist after the image build.
     * Missing variants gracefully fall back to the existing JPEG <img>.
     */
    public static function webpSrcset(string $image): string
    {
        $widths = self::WIDTHS[$image] ?? null;

        if ($widths === null) {
            return '';
        }

        $original = public_path('images/'.$image.'.jpeg');

        if (! is_file($original)) {
            return '';
        }

        $dimensions = @getimagesize($original);

        if (! is_array($dimensions) || ! isset($dimensions[0])) {
            return '';
        }

        $originalWidth = (int) $dimensions[0];
        $sources = [];

        foreach ($widths as $width) {
            if ($width >= $originalWidth) {
                continue;
            }

            $name = $image.'-'.$width.'.webp';

            if (is_file(public_path('images/'.$name))) {
                $sources[$width] = asset('images/'.$name).' '.$width.'w';
            }
        }

        $full = $image.'-full.webp';

        if (is_file(public_path('images/'.$full))) {
            $sources[$originalWidth] = asset('images/'.$full).' '.$originalWidth.'w';
        }

        ksort($sources);

        return implode(', ', $sources);
    }
}
