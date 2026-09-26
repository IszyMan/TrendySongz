<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    private function tracks(string $type)
    {
        return DB::table('listing as l')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'l.Artists_Id')
            ->select('l.*', 'a.ArtistsName')
            ->where('l.IsPublished', 'YES')
            ->where('l.ListingType', $type);
    }

    private function listing($rows, string $title, string $section)
    {
        return view('pages.cards', compact('rows', 'title', 'section'));
    }

    public function home()
    {
        $music = $this->tracks('audio')->orderByDesc('l.id')->limit(12)->get();
        $videos = $this->tracks('video')->orderByDesc('l.id')->limit(8)->get();

        $albums = DB::table('albums')
            ->where('IsPublished', 'YES')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $mixes = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->where('m.IsPublished', 'YES')
            ->orderByDesc('m.id')
            ->limit(8)
            ->get();

        $news = DB::table('blogs')
            ->where('IsPublished', 'YES')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('home', compact(
            'music',
            'videos',
            'albums',
            'mixes',
            'news'
        ));
    }

    // /musics: every published audio track, regardless of country.
    public function music_all()
    {
        $rows = $this->tracks('audio')
            ->orderByDesc('l.id')
            ->paginate(24);

        return view('pages.musics', compact('rows'));
    }

    // /musics/naija, /musics/ghana, /musics/african.
    public function musics(string $country)
    {
        abort_unless(
            in_array($country, ['naija', 'ghana', 'african'], true),
            404
        );

        $rows = $this->tracks('audio')
            ->where('l.country_id', $country)
            ->orderByDesc('l.id')
            ->paginate(24);

        return view('pages.music_country', compact('rows', 'country'));
    }

    // Keeps the method name used by your current local routes working.
    public function music(?string $country = null)
    {
        return $country === null
            ? $this->music_all()
            : $this->musics($country);
    }

    
    // /download-latest-videos
    public function videos_all()
    {
        $videos = $this->tracks('video')
            ->orderByDesc('l.id')
            ->paginate(24);

        return view('pages.videos', compact('videos'));
    }

    // Keeps the method name used by your current local routes working.
    public function videos()
    {
        return $this->videos_all();
    }

    public function videos_country(string $country)
    {
        abort_unless(
            in_array($country, ['naija', 'ghana', 'african'], true),
            404
        );

        $rows = $this->tracks('video')
            ->where('l.country_id', $country)
            ->orderByDesc('l.id')
            ->paginate(24);

        return $this->listing(
            $rows,
            ucfirst($country) . ' Music Videos',
            'videos'
        );
    }

    public function musicDetail(int $id)
    {
        $artist = $this->tracks('audio')
            ->leftJoin('users as u', 'u.id', '=', 'l.posted_by')
            ->addSelect('u.name as posted_by_name')
            ->where('l.id', $id)
            ->first();

        abort_unless($artist, 404);

        $title = trim(
            ($artist->ArtistsName ?: 'Unknown Artist')
            . ' - '
            . $artist->TrackTitle
            . ($artist->Featuring ? ' ft ' . $artist->Featuring : '')
        );

        $album = $artist->album_id > 0
            ? DB::table('albums')
                ->where('IsPublished', 'YES')
                ->where('id', $artist->album_id)
                ->first()
            : null;

        $related = $this->tracks('audio')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->where('l.id', '!=', $id)
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $artistAlbums = DB::table('albums')
            ->where('IsPublished', 'YES')
            ->where('artist_id', $artist->Artists_Id)
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $artistVideos = $this->tracks('video')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $collaborations = $this->tracks('audio')
            ->where('l.Artists_Id', '!=', $artist->Artists_Id)
            ->where('l.Featuring', 'like', '%' . $artist->ArtistsName . '%')
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $latestMusic = $this->tracks('audio')
            ->where('l.id', '!=', $id)
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $latestVideos = $this->tracks('video')
            ->orderByDesc('l.id')
            ->limit(4)
            ->get();

        return view('pages.musics_detail', compact(
            'artist',
            'title',
            'album',
            'related',
            'artistAlbums',
            'artistVideos',
            'collaborations',
            'latestMusic',
            'latestVideos'
        ));
    }

    public function videoDetail(int $id)
    {
        $artist = $this->tracks('video')
            ->leftJoin('users as u', 'u.id', '=', 'l.posted_by')
            ->addSelect('u.name as postedby')
            ->where('l.id', $id)
            ->first();

        abort_unless($artist, 404);

        $artist->postedby = $artist->postedby ?: 'TrendySongz';

        $title = trim(
            ($artist->ArtistsName ?: 'Unknown Artist')
            . ' - '
            . $artist->TrackTitle
        );

        $video = $this->tracks('video')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->where('l.id', '!=', $id)
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $audio = $this->tracks('audio')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->orderByDesc('l.id')
            ->limit(6)
            ->get();

        $music = DB::table('listing as l')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'l.Artists_Id')
            ->select('l.*', 'a.ArtistsName')
            ->where('l.IsPublished', 'YES')
            ->whereIn('l.ListingType', ['audio', 'video'])
            ->where('l.id', '!=', $id)
            ->orderByDesc('l.id')
            ->limit(8)
            ->get();

        $additional_link = $artist->additional_link_id
            ? $this->tracks('audio')
                ->where('l.id', $artist->additional_link_id)
                ->first()
            : null;

        $type = 'MP3';

        $comment = DB::table('comments')
            ->where('blog_id', $id)
            ->where('category_id', '4')
            ->where('is_allowed', 1)
            ->orderByDesc('id')
            ->get();

        $comment_count = $comment->count();

        return view('pages.videos_detail', compact(
            'artist',
            'title',
            'video',
            'audio',
            'music',
            'additional_link',
            'type',
            'comment',
            'comment_count'
        ));
    }


    public function videos_posted_by(string $slug)
    {
        $poster = DB::table('users')
            ->get(['id', 'name'])
            ->first(fn ($user) => Str::slug($user->name) === $slug);

        abort_unless($poster, 404);

        $videos = $this->tracks('video')
            ->where('l.posted_by', $poster->id)
            ->orderByDesc('l.id')
            ->paginate(24);

        return view('pages.videos_posted_by', compact('poster', 'videos'));
    }


    public function videos_released_year(string $year)
    {
        abort_unless(
            preg_match('/^\d{4}$/', $year) === 1,
            404
        );

        $videos = $this->tracks('video')
            ->where('l.YearOfRelease', $year)
            ->orderByDesc('l.id')
            ->paginate(24);

        return view('pages.videos_year', compact('videos', 'year'));
    }

    public function albums()
    {
        $albums = DB::table('albums as al')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'al.artist_id')
            ->select(
                'al.*',
                'a.ArtistsName',
                DB::raw(
                    "(SELECT COUNT(*)
                    FROM listing AS l
                    WHERE l.album_id = al.id
                        AND l.ListingType = 'audio'
                        AND l.IsPublished = 'YES') AS track_count"
                )
            )
            ->where('al.IsPublished', 'YES')
            ->orderByDesc('al.id')
            ->paginate(20);

        return view('pages.album', compact('albums'));
    }

    public function albumDetail(int $id)
    {
        $album = DB::table('albums as al')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'al.artist_id')
            ->leftJoin('users as u', 'u.id', '=', 'al.posted_by')
            ->select(
                'al.*',
                'a.ArtistsName',
                'a.Stage_Name',
                'u.name as posted_by_name'
            )
            ->where('al.IsPublished', 'YES')
            ->where('al.id', $id)
            ->first();

        abort_unless($album, 404);

        $tracks = $this->tracks('audio')
            ->where('l.album_id', $id)
            ->orderBy('l.track_number')
            ->orderBy('l.id')
            ->get();

        return view('pages.album_details', compact('album', 'tracks'));
    }

    public function djmix()
    {
        $mixes = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->where('m.IsPublished', 'YES')
            ->orderByDesc('m.id')
            ->paginate(24);

        return view('pages.djmix', compact('mixes'));
    }

    public function DJMixDetails($id, $slug = null)
    {
        $dj = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->leftJoin('users as u', 'u.id', '=', 'm.posted_by')
            ->select(
                'm.*',
                'd.dj_name',
                'u.name as posted_user'
            )
            ->where('m.IsPublished', 'YES')
            ->where('m.id', $id)
            ->firstOrFail();

        $djOtherMixes = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->where('m.IsPublished', 'YES')
            ->where('m.dj_id', $dj->dj_id)
            ->where('m.id', '!=', $dj->id)
            ->orderByDesc('m.id')
            ->limit(6)
            ->get();

        $otherDjMixes = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->where('m.IsPublished', 'YES')
            ->where('m.dj_id', '!=', $dj->dj_id)
            ->orderByDesc('m.id')
            ->limit(6)
            ->get();

        return view('pages.djmix_detail', [
            'dj' => $dj,
            'djOtherMixes' => $djOtherMixes,
            'otherDjMixes' => $otherDjMixes,
        ]);
    }

    public function djDetail(string $slug)
    {
        $dj = DB::table('dj')
            ->where('IsPublished', 'YES')
            ->get()
            ->first(fn ($row) => Str::slug($row->dj_name) === $slug);

        abort_unless($dj, 404);

        $mixes = DB::table('dj_mixs')
            ->where('dj_id', $dj->id)
            ->where('IsPublished', 'YES')
            ->orderByDesc('id')
            ->get();

        return view('pages.dj', compact('dj', 'mixes'));
    }

    public function artists()
    {
        $artistsQuery = DB::table('artists as a')
            ->select('a.*')
            ->selectSub(
                DB::table('albums as al')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('al.artist_id', 'a.Artists_Id')
                    ->where('al.IsPublished', 'YES'),
                'album_count'
            )
            ->selectSub(
                DB::table('listing as l')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('l.Artists_Id', 'a.Artists_Id')
                    ->where('l.IsPublished', 'YES')
                    ->where('l.ListingType', 'audio')
                    ->where(function ($query) {
                        $query->whereNull('l.album_id')
                            ->orWhere('l.album_id', 0);
                    }),
                'single_count'
            )
            ->where('a.IsPublished', 'YES');

        $sections = [
            'naija' => [
                'country' => 'naija',
                'heading' => 'Nigerian Artists',
                'page_name' => 'naija_page',
            ],
            'ghana' => [
                'country' => 'ghana',
                'heading' => 'Ghanaian Artists',
                'page_name' => 'ghana_page',
            ],
            'african' => [
                'country' => 'african',
                'heading' => 'African Artists',
                'page_name' => 'african_page',
            ],
        ];

        // An AJAX pagination click loads only its requested section.
        if (request()->ajax() && isset($sections[request('section')])) {
            $section = request('section');
            $settings = $sections[$section];

            $rows = (clone $artistsQuery)
                ->where('a.country_id', $settings['country'])
                ->orderBy('a.order_id')
                ->orderByDesc('a.id')
                ->paginate(12, ['*'], $settings['page_name']);

            return view('pages.partials.artist-country-section', [
                'section' => $section,
                'heading' => $settings['heading'],
                'rows' => $rows,
            ]);
        }

        $popularNames = [
            'Davido',
            'Wizkid',
            'Burna Boy',
            'Rema',
            'Tiwa Savage',
            'Olamide',
        ];

        $popular = (clone $artistsQuery)
            ->whereIn('a.ArtistsName', $popularNames)
            ->orderByRaw(
                'FIELD(a.ArtistsName, ' .
                implode(', ', array_fill(0, count($popularNames), '?')) .
                ')',
                $popularNames
            )
            ->get();

        $naija = (clone $artistsQuery)
            ->where('a.country_id', 'naija')
            ->orderBy('a.order_id')
            ->orderByDesc('a.id')
            ->paginate(12, ['*'], 'naija_page');

        $ghana = (clone $artistsQuery)
            ->where('a.country_id', 'ghana')
            ->orderBy('a.order_id')
            ->orderByDesc('a.id')
            ->paginate(12, ['*'], 'ghana_page');

        $african = (clone $artistsQuery)
            ->where('a.country_id', 'african')
            ->orderBy('a.order_id')
            ->orderByDesc('a.id')
            ->paginate(12, ['*'], 'african_page');

        return view('pages.artists', [
            'popular' => $popular,
            'naija' => $naija,
            'ghana' => $ghana,
            'african' => $african,
        ]);
    }

    public function artists_country(string $country)
    {
        abort_unless(
            in_array($country, ['naija', 'ghana', 'african'], true),
            404
        );

        $rows = DB::table('artists')
            ->where('IsPublished', 'YES')
            ->where('country_id', $country)
            ->orderBy('ArtistsName')
            ->paginate(24);

        return $this->listing(
            $rows,
            ucfirst($country) . ' Artists',
            'artists'
        );
    }

    public function artistDetail($artistSlug, $slug = null)
    {
        $artistQuery = DB::table('artists as a')
            ->where('a.IsPublished', 'YES');

        if (ctype_digit((string) $artistSlug)) {
            $artist = $artistQuery
                ->where('a.id', $artistSlug)
                ->firstOrFail();
        } else {
            $artist = $artistQuery
                ->where(function ($query) use ($artistSlug) {
                    $query
                        ->whereRaw(
                            "LOWER(REPLACE(a.ArtistsName, ' ', '-')) = ?",
                            [strtolower($artistSlug)]
                        )
                        ->orWhereRaw(
                            "LOWER(REPLACE(a.Stage_Name, ' ', '-')) = ?",
                            [strtolower($artistSlug)]
                        );
                })
                ->firstOrFail();
        }

        $albums = DB::table('albums as al')
            ->where('al.artist_id', $artist->Artists_Id)
            ->where('al.IsPublished', 'YES')
            ->orderByDesc('al.id')
            ->get();

        $albumTracks = DB::table('listing as l')
            ->whereIn('l.album_id', $albums->pluck('id'))
            ->where('l.ListingType', 'audio')
            ->where('l.IsPublished', 'YES')
            ->orderBy('l.track_number')
            ->orderBy('l.id')
            ->get()
            ->groupBy('album_id');

        $singles = DB::table('listing as l')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->where('l.ListingType', 'audio')
            ->where('l.IsPublished', 'YES')
            ->where(function ($query) {
                $query->whereNull('l.album_id')
                    ->orWhere('l.album_id', 0);
            })
            ->orderByDesc('l.id')
            ->get();

        $videos = DB::table('listing as l')
            ->where('l.Artists_Id', $artist->Artists_Id)
            ->where('l.ListingType', 'video')
            ->where('l.IsPublished', 'YES')
            ->orderByDesc('l.id')
            ->get();

        $featuredSongs = DB::table('listing as l')
            ->leftJoin('artists as a', 'a.Artists_Id', '=', 'l.Artists_Id')
            ->select('l.*', 'a.ArtistsName as primary_artist')
            ->where('l.ListingType', 'audio')
            ->where('l.IsPublished', 'YES')
            ->where('l.Artists_Id', '!=', $artist->Artists_Id)
            ->where('l.Featuring', 'like', '%' . $artist->ArtistsName . '%')
            ->orderByDesc('l.id')
            ->get();

        return view('pages.artists_detail', [
            'artist' => $artist,
            'albums' => $albums,
            'albumTracks' => $albumTracks,
            'singles' => $singles,
            'videos' => $videos,
            'featuredSongs' => $featuredSongs,
        ]);
    }

    public function MusicNews()
    {
        $rows = DB::table('blogs as b')
            ->leftJoin('users as u', 'u.id', '=', 'b.posted_by')
            ->select(
                'b.*',
                'u.name as posted_by_name'
            )
            ->where('b.IsPublished', 'YES')
            ->orderByDesc('b.id')
            ->paginate(20);

        return view('pages.celebrity_news', ['rows' => $rows]);
    }

    public function MusicNewsDetails($id, $slug = null)
    {
        $blog = DB::table('blogs as b')
            ->leftJoin('users as u', 'u.id', '=', 'b.posted_by')
            ->select(
                'b.*',
                'u.name as posted_by_name'
            )
            ->where('b.IsPublished', 'YES')
            ->where('b.id', $id)
            ->firstOrFail();

        $related_blog = DB::table('blogs as b')
            ->where('b.IsPublished', 'YES')
            ->where('b.id', '!=', $blog->id)
            ->orderByDesc('b.id')
            ->limit(4)
            ->get();

        return view('pages.celebritynews_details', [
            'blog' => $blog,
            'related_blog' => $related_blog,
        ]);
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('search', ''));

        $rows = $term === ''
            ? collect()
            : $this->tracks('audio')
                ->where(function ($query) use ($term) {
                    $query->where('l.TrackTitle', 'like', "%{$term}%")
                        ->orWhere('a.ArtistsName', 'like', "%{$term}%");
                })
                ->orderByDesc('l.id')
                ->limit(50)
                ->get();

        $title = $term === ''
            ? 'Search TrendySongz'
            : 'Search: ' . $term;

        return $this->listing($rows, $title, 'search');
    }
}