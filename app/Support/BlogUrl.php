<?php

namespace App\Support;

use Illuminate\Support\Str;

class BlogUrl
{
    public static function slug(object $blog): string
    {
        return Str::slug(
            trim((string) ($blog->title ?? ''))
        );
    }

    public static function detail(object $blog): string
    {
        return route('pages.celebritynews_details', [
            'id' => $blog->id,
            'slug' => self::slug($blog),
        ]);
    }
}