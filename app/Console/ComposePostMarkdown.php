<?php

namespace App\Console;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class ComposePostMarkdown
{
    public static function handle(
        string $bodyTmpFilename,
        string $defaultBody = '',
    ): ?string {
        $sensibleEditorDefaults = config('editor.options');

        $bodyTmpFileIsSaved = false;
        $bodyTmpFileIsEmpty = true;

        Storage::put($bodyTmpFilename, $defaultBody);

        $bodyTmpFilePath = Storage::path($bodyTmpFilename);

        do {
            Process::forever()->tty()->run(
                "vim '$sensibleEditorDefaults' $bodyTmpFilePath", // Remove these single quotes, and you'll hate your life
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
