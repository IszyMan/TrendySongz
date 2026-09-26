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

    // All published music videos.
    public function videos_all()
    {
        $rows = $this->tracks('video')
            ->orderByDesc('l.id')
            ->paginate(24);

        return $this->listing($rows, 'Latest Videos', 'videos');
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
        $row = $this->tracks('video')
            ->where('l.id', $id)
            ->first();

        abort_unless($row, 404);

        return view('pages.track', compact('row'));
    }

    public function albums()
    {
        $rows = DB::table('albums')
            ->where('IsPublished', 'YES')
            ->orderByDesc('id')
            ->paginate(24);

        return $this->listing($rows, 'Albums / EPs', 'albums');
    }

    public function albumDetail(int $id)
    {
        $row = DB::table('albums')
            ->where('IsPublished', 'YES')
            ->where('id', $id)
            ->first();

        abort_unless($row, 404);

        $tracks = DB::table('listing')
            ->where('album_id', $id)
            ->where('IsPublished', 'YES')
            ->orderBy('track_number')
            ->get();

        return view('pages.album', compact('row', 'tracks'));
    }

    public function mixes()
    {
        $rows = DB::table('dj_mixs as m')
            ->leftJoin('dj as d', 'd.id', '=', 'm.dj_id')
            ->select('m.*', 'd.dj_name')
            ->where('m.IsPublished', 'YES')
            ->orderByDesc('m.id')
            ->paginate(24);

        return $this->listing($rows, 'DJ Mixes', 'mixes');
    }

    public function mixDetail(int $id)
    {
        $row = DB::table('dj_mixs')
            ->where('IsPublished', 'YES')
            ->where('id', $id)
            ->first();

        abort_unless($row, 404);

        return view('pages.mix', compact('row'));
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
        $rows = DB::table('artists')
            ->where('IsPublished', 'YES')
            ->orderBy('ArtistsName')
            ->paginate(24);

        return $this->listing($rows, 'Artists', 'artists');
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

    public function artistDetail(int $id)
    {
        $row = DB::table('artists')
            ->where('IsPublished', 'YES')
            ->where('id', $id)
            ->first();

        abort_unless($row, 404);

        $tracks = $this->tracks('audio')
            ->where('l.Artists_Id', $row->Artists_Id)
            ->orderByDesc('l.id')
            ->limit(24)
            ->get();

        return view('pages.artist', compact('row', 'tracks'));
    }

    public function news()
    {
        $rows = DB::table('blogs')
            ->where('IsPublished', 'YES')
            ->orderByDesc('id')
            ->paginate(24);

        return $this->listing($rows, 'Music News', 'news');
    }

    public function newsDetail(int $id)
    {
        $row = DB::table('blogs')
            ->where('IsPublished', 'YES')
            ->where('id', $id)
            ->first();

        abort_unless($row, 404);

        return view('pages.news', compact('row'));
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