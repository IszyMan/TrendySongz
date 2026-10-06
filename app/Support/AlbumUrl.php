<?php

namespace App\Support;

use Illuminate\Support\Str;

class AlbumUrl
{
    public static function slug(object $album): string
    {
        $artistName = trim((string) ($album->ArtistsName ?? ''));

        if ($artistName === '') {
            $artistName = trim((string) ($album->Stage_Name ?? ''));
        }

        $slug = Str::slug(
            $artistName.' '.trim((string) ($album->title ?? ''))
        );

        // Add "album" only when it is not already at the end.
        if ($slug !== 'album' && ! str_ends_with($slug, '-album')) {
            $slug .= ($slug !== '' ? '-' : '').'album';
        }

        return $slug;
    }

    public static function detail(object $album): string
    {
        return route('pages.album_details', [
            'id' => $album->id,
            'slug' => self::slug($album),
        ]);
    }
}