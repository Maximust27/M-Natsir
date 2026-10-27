<?php

namespace App\Support\Media;

final class HeroImages
{
    private const IMAGES = [
        'home-hero' => ['file' => 'home-hero.jpeg', 'widths' => [480, 960, 1440]],
        'about-hero' => ['file' => 'about-hero.png', 'widths' => [320, 640]],
    ];

    /**
     * Return only WebP files that actually exist after the image build.
     * Missing variants gracefully fall back to the original <img> asset.
     */
    public static function webpSrcset(string $image): string
    {
        $details = self::IMAGES[$image] ?? null;

        if ($details === null) {
            return '';
        }

        $widths = $details['widths'];
        $original = public_path('images/'.$details['file']);

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
