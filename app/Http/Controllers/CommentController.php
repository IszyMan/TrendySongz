<?php

namespace App\Http\Controllers;

use App\Support\CommentTarget;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(
        Request $request
    ): JsonResponse|RedirectResponse {
        $plainText = $this->plainTextRule();

        $data = $request->validate([
            'target' => [
                'required',
                'string',
                'max:20000',
            ],

            'name' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:191',
                $plainText,
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:250',
            ],

            'message' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:3000',
                $plainText,
            ],

            // Hidden spam trap; real visitors leave it empty.
            'website' => [
                'nullable',
                'string',
                'max:0',
            ],
        ], [
            'website.max' => 'Your comment could not be submitted. Please try again.',
        ]);

        $target = CommentTarget::decode($data['target']);

        // Additional checks before saving or redirecting.
        if (
            mb_strlen($target['title']) > 500
            || mb_strlen($target['path']) > 1024
            || str_contains($target['path'], '\\')
            || preg_match('/[\x00-\x20\x7F]/', $target['path'])
        ) {
            throw ValidationException::withMessages([
                'target' => 'Please refresh this page and try again.',
            ]);
        }

        DB::table('comments')->insert([
            'blog_id' => $target['type'] === 'blog'
                ? $target['id']
                : 0,

            'name' => trim($data['name']),
            'email' => trim($data['email'] ?? ''),
            'coments' => trim($data['message']),

            // Required legacy field; post_type/post_id identify new comments.
            'category_id' => '',

            'post_type' => $target['type'],
            'post_id' => $target['id'],
            'post_title' => $target['title'],
            'post_path' => $target['path'],

            // Approval is controlled only by the administrator.
            'is_allowed' => 0,
            'likes_count' => 0,
            'created_at' => now(),
        ]);

        $message = 'Your comment was submitted successfully and is waiting for approval.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], 201);
        }

        // Fallback for browsers with JavaScript disabled.
        return redirect()
            ->to(
                $target['path']
                . '#comments-'
                . $target['type']
                . '-'
                . $target['id']
            )
            ->with('comment_status', $message);
    }

    public function like(string $comment): JsonResponse
    {
        $commentId = filter_var(
            $comment,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        abort_if($commentId === false, 404);

        $key = 'ts-comment-like:' . hash(
            'sha256',
            session()->getId() . ':' . $commentId
        );

        $added = false;

        try {
            $result = DB::transaction(function () use (
                $commentId,
                $key,
                &$added
            ) {
                $record = DB::table('comments')
                    ->where('id', $commentId)
                    ->where('is_allowed', 1)
                    ->lockForUpdate()
                    ->first();

                abort_unless($record, 404);

                $added = Cache::add($key, true, now()->addYear());

                if ($added) {
                    DB::table('comments')
                        ->where('id', $commentId)
                        ->update([
                            'likes_count' => DB::raw(
                                'COALESCE(likes_count, 0) + 1'
                            ),
                        ]);
                }

                return [
                    'success' => true,
                    'likes_count' => (int) DB::table('comments')
                        ->where('id', $commentId)
                        ->value('likes_count'),
                ];
            });
        } catch (\Throwable $exception) {
            if ($added) {
                Cache::forget($key);
            }

            throw $exception;
        }

        return response()->json($result);
    }

    private function plainTextRule(): Closure
    {
        return static function (
            string $attribute,
            mixed $value,
            Closure $fail
        ): void {
            if (! is_string($value)) {
                return;
            }

            if (! mb_check_encoding($value, 'UTF-8')) {
                $fail('The :attribute must contain valid text.');

                return;
            }

            $check = $value;

            // Decode encoded dots, characters and URLs.
            for ($i = 0; $i < 6; $i++) {
                $decoded = html_entity_decode(
                    rawurldecode($check),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                );

                if ($decoded === $check) {
                    break;
                }

                $check = $decoded;
            }

            if (class_exists(\Normalizer::class)) {
                $normalized = \Normalizer::normalize(
                    $check,
                    \Normalizer::FORM_KC
                );

                if ($normalized !== false) {
                    $check = $normalized;
                }
            }

            // Normalize full-width characters without requiring intl.
            $check = preg_replace_callback(
                '/[\x{FF01}-\x{FF5E}]/u',
                static fn ($match) => mb_chr(
                    mb_ord($match[0], 'UTF-8') - 0xFEE0,
                    'UTF-8'
                ),
                $check
            ) ?? $check;

            $check = str_replace(
                ['。', '．', '｡', '：', '／', '＠'],
                ['.', '.', '.', ':', '/', '@'],
                $check
            );

            $check = preg_replace('/\p{Cf}/u', '', $check) ?? $check;

            $check = preg_replace(
                '/[\p{Z}\s]+/u',
                ' ',
                $check
            ) ?? $check;

            $check = preg_replace(
                '~(?:\[\s*dot\s*\]|\(\s*dot\s*\)|\{\s*dot\s*\}|\s+dot\s+)~iu',
                '.',
                $check
            ) ?? $check;

            $patterns = [
                // HTTP, HTTPS, FTP, hxxp and common URI schemes.
                '~\b(?:h\s*[tx]\s*[tx]\s*p\s*s?|f\s*t\s*p\s*s?|mailto|javascript|data|tel)\s*(?::|\b)~iu',
            
                // Other scheme:// links.
                '~\b[a-z][a-z0-9+.-]*\s*:\s*/\s*/~iu',
            
                // www, including spaced letters.
                '~\bw\s*w\s*w\s*\.~iu',
            
                // Compact domains: example.com, example.music.
                '~[\p{L}\p{N}](?:[\p{L}\p{N}-]*[\p{L}\p{N}])?\.(?:[\p{L}]{2,63}|xn--[a-z0-9-]+)(?![\p{L}\p{N}-])~iu',
            
                // Space before the dot: example . com, example .com.
                '~[\p{L}\p{N}](?:[\p{L}\p{N}-]*[\p{L}\p{N}])?\s+\.\s*(?:[\p{L}]{2,63}|xn--[a-z0-9-]+)(?![\p{L}\p{N}-])~iu',
            
                // Spaces only after the dot: example. com.
                // Limit extensions here to avoid matching normal sentences.
                '~[\p{L}\p{N}](?:[\p{L}\p{N}-]*[\p{L}\p{N}])?\.\s+(?:com|org|net|edu|gov|info|biz|io|co|me|app|dev|online|site|xyz|ng|gh|uk|us)(?![\p{L}\p{N}-])~iu',
            
                // IPv4 addresses.
                '~\b(?:\d{1,3}\s*\.\s*){3}\d{1,3}\b~u',
            
                // IPv6-like addresses.
                '~[0-9a-f]{0,4}:(?:[0-9a-f]{0,4}:)+[0-9a-f:]{0,4}~iu',
            
                // Email addresses and handles.
                '~@~u',
            
                // BBCode links.
                '~\[(?:/?url|/?link)\b~iu',
            
                // HTML and Markdown links.
                '~<[^>]*>|\[[^\]]*\]\s*\(~u',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $check) === 1) {
                    $fail(
                        'The :attribute must be plain text. Links, web addresses, email addresses and HTML are not allowed.'
                    );

                    return;
                }
            }
        };
    }
}