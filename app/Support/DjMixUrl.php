<?php

namespace App\Support;

use Illuminate\Support\Str;

class DjMixUrl
{
    public static function slug(object $mix): string
    {
        return Str::slug(
            trim(
                ($mix->dj_name ?? '')
                .' '
                .($mix->mix_title ?? '')
            )
        );
    }

    public static function detail(object $mix): string
    {
        return route('pages.djmix_detail', [
            'id' => $mix->id,
            'slug' => self::slug($mix),
        ]);
    }
}