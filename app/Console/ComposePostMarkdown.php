<?php

namespace App\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComposePostMarkdown
{
    public static function handle(
        string $bodyTmpFilename,
        ?string $defaultBody = null,
    ): ?string {
        $sensibleEditorDefaults = config('editor.options');

        $bodyTmpFileIsSaved = false;
        $bodyTmpFileIsEmpty = true;
        $bodyTmpFileHasFrontMatter = false;

        $defaultBody ??= <<<'EOD'
        ---
        description:
        tags:
            -
        ---

        #
        EOD;

        Storage::put($bodyTmpFilename, $defaultBody);

        $bodyTmpFilePath = Storage::path($bodyTmpFilename);

        do {
            Process::forever()->tty()->run(
                "vim '$sensibleEditorDefaults' $bodyTmpFilePath", // Remove these single quotes, and you'll hate your life
            );

            $bodyTmpFileIsSaved = Storage::exists($bodyTmpFilename);

            $bodyTmpFileIsEmpty = $bodyTmpFileIsSaved
                && Storage::size($bodyTmpFilename) == 0;

            $bodyTmpFileHasFrontMatter = Str::startsWith(File::get($bodyTmpFilePath), '---');
        } while (! $bodyTmpFileIsSaved || $bodyTmpFileIsEmpty || ! $bodyTmpFileHasFrontMatter);

        $markdown = Storage::get($bodyTmpFilename);

        Storage::delete($bodyTmpFilename);

        return $markdown;
    }
}
