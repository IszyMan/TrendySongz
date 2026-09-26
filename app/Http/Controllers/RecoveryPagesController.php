<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecoveryPagesController extends Controller
{
    private function tracks(string $type)
    {
        return DB::table('listing as l')->leftJoin('artists as a', 'a.Artists_Id', '=', 'l.Artists_Id')
            ->select('l.*', 'a.ArtistsName')->where('l.IsPublished', 'YES')->where('l.ListingType', $type);
    }

    private function cards($rows, string $title)
    {
        return view('pages.cards', compact('rows', 'title'));
    }

    public function home()
    {
        $music = $this->tracks('audio')->orderByDesc('l.id')->limit(12)->get();
        $videos = $this->tracks('video')->orderByDesc('l.id')->limit(8)->get();
        $albums = DB::table('albums')->where('IsPublished', 'YES')->orderByDesc('id')->limit(8)->get();
        $mixes = DB::table('dj_mixs')->where('IsPublished', 'YES')->orderByDesc('id')->limit(8)->get();
        return view('pages.home', compact('music', 'videos', 'albums', 'mixes'));
    }

    public function music(string $country = 'naija')
    {
        $country = ['naija' => 'naija', 'ghana' => 'ghana', 'african' => 'african'][$country] ?? abort(404);
        $rows = $this->tracks('audio')->where('l.country_id', $country)->orderByDesc('l.id')->paginate(24);
        return $this->cards($rows, ucfirst($country) . ' Music');
    }

    public function videos()
    {
        return $this->cards($this->tracks('video')->orderByDesc('l.id')->paginate(24), 'Latest Videos');
    }

    public function musicDetail(int $id)
    {
        $row = $this->tracks('audio')->where('l.id', $id)->first();
        abort_unless($row, 404);
        return view('pages.track', compact('row'));
    }

    public function videoDetail(int $id)
    {
        $row = $this->tracks('video')->where('l.id', $id)->first();
        abort_unless($row, 404);
        return view('pages.track', compact('row'));
    }

    public function albums()
    {
        return $this->cards(DB::table('albums')->where('IsPublished', 'YES')->orderByDesc('id')->paginate(24), 'Albums / EPs');
    }

    public function albumDetail(int $id)
    {
        $row = DB::table('albums')->where('IsPublished', 'YES')->where('id', $id)->first();
        abort_unless($row, 404);
        $tracks = DB::table('listing')->where('album_id', $id)->where('IsPublished', 'YES')->orderBy('track_number')->get();
        return view('pages.album', compact('row', 'tracks'));
    }

    public function mixes()
    {
        return $this->cards(DB::table('dj_mixs')->where('IsPublished', 'YES')->orderByDesc('id')->paginate(24), 'DJ Mixes');
    }

    public function mixDetail(int $id)
    {
        $row = DB::table('dj_mixs')->where('IsPublished', 'YES')->where('id', $id)->first();
        abort_unless($row, 404);
        return view('pages.mix', compact('row'));
    }

    public function djDetail(string $slug)
    {
        $dj = DB::table('dj')->where('IsPublished', 'YES')->get()->first(fn ($row) => Str::slug($row->dj_name) === $slug);
        abort_unless($dj, 404);
        $mixes = DB::table('dj_mixs')->where('dj_id', $dj->id)->where('IsPublished', 'YES')->orderByDesc('id')->get();
        return view('pages.dj', compact('dj', 'mixes'));
    }

    public function artists()
    {
        return $this->cards(DB::table('artists')->where('IsPublished', 'YES')->orderBy('ArtistsName')->paginate(24), 'Artists');
    }

    public function artistDetail(int $id)
    {
        $row = DB::table('artists')->where('IsPublished', 'YES')->where('id', $id)->first();
        abort_unless($row, 404);
        $tracks = $this->tracks('audio')->where('l.Artists_Id', $row->Artists_Id)->orderByDesc('l.id')->limit(24)->get();
        return view('pages.artist', compact('row', 'tracks'));
    }

    public function news()
    {
        return $this->cards(DB::table('blogs')->where('IsPublished', 'YES')->orderByDesc('id')->paginate(24), 'Music News');
    }

    public function newsDetail(int $id)
    {
        $row = DB::table('blogs')->where('IsPublished', 'YES')->where('id', $id)->first();
        abort_unless($row, 404);
        return view('pages.news', compact('row'));
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('search', ''));
        $rows = $term === '' ? collect() : $this->tracks('audio')
            ->where(function ($query) use ($term) {
                $query->where('l.TrackTitle', 'like', '%' . $term . '%')
                    ->orWhere('a.ArtistsName', 'like', '%' . $term . '%');
            })->orderByDesc('l.id')->limit(50)->get();
        return $this->cards($rows, 'Search: ' . $term);
    }
}
