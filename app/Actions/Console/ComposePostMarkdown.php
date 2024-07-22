<?php

namespace App\Actions\Console;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\textarea;

class ComposePostMarkdown
{
    public static function handle(
        string $preferredTextEditor,
        string $bodyTmpFilename,
        string $defaultBody = '',
    ): ?string {
        if ($preferredTextEditor == 'builtin') {
            info("No worries, here's one for you.");

            return textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                default: $defaultBody,
                rows: 25,
            );
        }

        $bodyTmpFileIsSaved = false;
        $bodyTmpFileIsEmpty = true;

        Storage::put($bodyTmpFilename, $defaultBody);

        $bodyTmpFilePath = Storage::path($bodyTmpFilename);

        do {
            Process::forever()->tty()->run(
                "$preferredTextEditor $bodyTmpFilePath", // Remove these single quotes, and you'll hate your life
            );

            $bodyTmpFileIsSaved = Storage::exists($bodyTmpFilename);

            $bodyTmpFileIsEmpty = $bodyTmpFileIsSaved
                && Storage::size($bodyTmpFilename) == 0;
        } while (! $bodyTmpFileIsSaved || $bodyTmpFileIsEmpty);

        $markdown = Storage::get($bodyTmpFilename);

        Storage::delete($bodyTmpFilename);

        return $markdown;
    }
}
