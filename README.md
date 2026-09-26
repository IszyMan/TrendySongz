# TrendySongz local source files

These files are an overlay for a fresh Laravel 12 project, not the Laravel framework itself. The original Blade layout and inline CSS are preserved.

Use one project directory: C:\\laragon\\www\\trendysongz. Merge the app, routes, resources, and public folders into it. Configure a new MySQL database in .env and import the supplied SQL into that database. Do not run migrations against the imported database.

Read-only listing/detail pages are provided for music, videos, albums, DJs, artists and news. Search starts with music. The original media assets and some legacy URL patterns are missing. Audio/video download buttons are intentionally deferred until media paths are verified. The CSS/Blade layout references other external assets not included in the upload. Admin write actions are not included.

The PHP source needs syntax and runtime verification on the local computer; PHP was unavailable where these source files were assembled. The SQL file is not included in this ZIP.
