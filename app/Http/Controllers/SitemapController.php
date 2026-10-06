<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Support\ListingUrl;
use App\Support\AlbumUrl;
use App\Support\DjMixUrl;
use App\Support\BlogUrl;


class SitemapController extends Controller
{
    private const PER_PAGE = 5000;

    private const TYPES = [
        'music',
        'video',
        'naija-music',
        'ghana-music',
        'african-music',
        'album',
        'blog',
        'dj-mix',
        'artists',
        'dj',
    ];

    public function index()
    {
        $lastModified = now()->toAtomString();
    
        $xml = $this->xmlHeader();
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
        foreach (self::TYPES as $type) {
            $count = $this->query($type)->count();
            $pages = (int) ceil($count / self::PER_PAGE);
    
            for ($page = 1; $page <= $pages; $page++) {
                $path = $page === 1
                    ? route('sitemap.first', ['type' => $type], false)
                    : route('sitemap.page', [
                        'type' => $type,
                        'page' => $page,
                    ], false);
    
                $xml .= '<sitemap>';
                $xml .= '<loc>'
                    .$this->escape($this->absoluteUrl($path))
                    .'</loc>';
                $xml .= '<lastmod>'
                    .$this->escape($lastModified)
                    .'</lastmod>';
                $xml .= '</sitemap>';
            }
        }
    
        $xml .= '</sitemapindex>';
    
        return $this->xmlResponse($xml);
    }

    public function show(string $type, string $page = '1')
    {
        abort_unless(in_array($type, self::TYPES, true), 404);
    
        $pageNumber = filter_var(
            $page,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
    
        abort_if($pageNumber === false, 404);
    
        $query = $this->query($type);
        $count = (clone $query)->count();
        $pages = (int) ceil($count / self::PER_PAGE);
    
        abort_if($pageNumber > $pages, 404);
    
        $idColumn = match ($type) {
            'music',
            'video',
            'naija-music',
            'ghana-music',
            'african-music' => 'listing.id',
    
            'album' => 'albums.id',
            'dj-mix' => 'dj_mixs.id',
    
            default => 'id',
        };
    
        $rows = $query
            ->orderByDesc($idColumn)
            ->offset(($pageNumber - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get();
    
        $lastModified = now()->toAtomString();
    
        $xml = $this->xmlHeader();
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
        $seen = [];
    
        foreach ($rows as $row) {
            $url = $this->itemUrl($type, $row);
    
            if (isset($seen[$url])) {
                continue;
            }
    
            $seen[$url] = true;
    
            $xml .= '<url>';
            $xml .= '<loc>'.$this->escape($url).'</loc>';
            $xml .= '<lastmod>'
                .$this->escape($lastModified)
                .'</lastmod>';
            $xml .= '</url>';
        }
    
        $xml .= '</urlset>';
    
        return $this->xmlResponse($xml);
    }
    public function stylesheet()
    {
        return response()
            ->view('sitemaps.style')
            ->header('Content-Type', 'text/xsl; charset=UTF-8');
    }

    private function query(string $type)
    {
        switch ($type) {
            case 'music':
                return DB::table('listing')
                    ->leftJoin(
                        'artists',
                        'artists.Artists_Id',
                        '=',
                        'listing.Artists_Id'
                    )
                    ->select('listing.*', 'artists.ArtistsName')
                    ->where('listing.IsPublished', 'YES')
                    ->where('listing.ListingType', 'audio');
            
            case 'video':
                return DB::table('listing')
                    ->leftJoin(
                        'artists',
                        'artists.Artists_Id',
                        '=',
                        'listing.Artists_Id'
                    )
                    ->select('listing.*', 'artists.ArtistsName')
                    ->where('listing.IsPublished', 'YES')
                    ->where('listing.ListingType', 'video');
            
            case 'naija-music':
            case 'ghana-music':
            case 'african-music':
                $country = str_replace('-music', '', $type);
            
                return DB::table('listing')
                    ->leftJoin(
                        'artists',
                        'artists.Artists_Id',
                        '=',
                        'listing.Artists_Id'
                    )
                    ->select('listing.*', 'artists.ArtistsName')
                    ->where('listing.IsPublished', 'YES')
                    ->where('listing.ListingType', 'audio')
                    ->where('listing.country_id', $country);
            
            case 'album':
                return DB::table('albums')
                    ->leftJoin(
                        'artists',
                        'artists.Artists_Id',
                        '=',
                        'albums.artist_id'
                    )
                    ->select(
                        'albums.*',
                        'artists.ArtistsName',
                        'artists.Stage_Name'
                    )
                    ->where('albums.IsPublished', 'YES');

            case 'blog':
                return DB::table('blogs')
                    ->where('IsPublished', 'YES');

            case 'dj-mix':
                return DB::table('dj_mixs')
                    ->leftJoin(
                        'dj',
                        'dj.id',
                        '=',
                        'dj_mixs.dj_id'
                    )
                    ->select('dj_mixs.*', 'dj.dj_name')
                    ->where('dj_mixs.IsPublished', 'YES');

            case 'artists':
                return DB::table('artists')
                    ->where('IsPublished', 'YES');

            case 'dj':
                return DB::table('dj')
                    ->where('IsPublished', 'YES')
                    ->whereNotNull('dj_name')
                    ->whereRaw("TRIM(dj_name) <> ''");
        }

        abort(404);
    }

    private function itemUrl(string $type, object $row): string
    {
        switch ($type) {
            case 'music':
            case 'naija-music':
            case 'ghana-music':
            case 'african-music':
                return ListingUrl::musicDetail($row);
            
            case 'video':
                return ListingUrl::videoDetail($row);
            
            case 'album':
                return AlbumUrl::detail($row);

            case 'blog':
                return BlogUrl::detail($row);

            case 'dj-mix':
                return DjMixUrl::detail($row);

            case 'artists':
                // Match the lookup used by your artistDetail() method.
                $name = (string) ($row->ArtistsName ?? '');

                if (trim($name) === '') {
                    $name = (string) ($row->Stage_Name ?? '');
                }

                $artistSlug = mb_strtolower(
                    str_replace(' ', '-', $name),
                    'UTF-8'
                );

                // Your controller also supports lookup by numeric ID.
                if ($artistSlug === '' || ctype_digit($artistSlug)) {
                    $artistSlug = (string) $row->id;
                }

                $path = route('pages.artists_details', [
                    'artistSlug' => $artistSlug,
                ], false);
                break;

            case 'dj':
                $path = route('pages.dj_details', [
                    'slug' => Str::slug($row->dj_name),
                ], false);
                break;

            default:
                abort(404);
        }

        return $this->absoluteUrl($path);
    }

    

    private function absoluteUrl(string $path): string
    {
        return rtrim((string) config('app.url'), '/')
            .'/'.ltrim($path, '/');
    }

    private function escape(string $value): string
    {
        return htmlspecialchars(
            $value,
            ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    private function xmlHeader(): string
    {
        $stylesheet = $this->absoluteUrl(
            route('sitemap.style', [], false)
        );

        return '<?xml version="1.0" encoding="UTF-8"?>'
            ."\n"
            .'<?xml-stylesheet type="text/xsl" href="'
            .$this->escape($stylesheet)
            .'"?>'
            ."\n";
    }

    private function xmlResponse(string $xml)
    {
        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}