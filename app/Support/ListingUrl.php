<?php

namespace App\Support;

use Illuminate\Support\Str;

class ListingUrl
{
   private static function slug(object $row, ?string $artistName = null): string
    {
        return Str::slug(
            trim(
                ($artistName ?? $row->ArtistsName ?? '')
                . ' '
                . ($row->TrackTitle ?? '')
                . (!empty($row->Featuring) ? ' ft ' . $row->Featuring : '')
            )
        );
    }
    
    public static function musicDetail(
        object $row,
        ?string $artistName = null
    ): string {
        return route('pages.musics_detail', [
            $row->id,
            self::slug($row, $artistName),
        ]);
    }

    public static function videoDetail(object $row): string
    {
        return route('pages.videos_detail', [
            $row->id,
            self::slug($row),
        ]);
    }

    public static function detail(object $row): string
    {
        return $row->ListingType === 'video'
            ? self::videoDetail($row)
            : self::musicDetail($row);
    }
}