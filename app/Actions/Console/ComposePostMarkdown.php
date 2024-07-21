<?php

namespace App\Actions\Console;

use App\Enums\TextEditor;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\pause;
use function Laravel\Prompts\textarea;

class ComposePostMarkdown
{
    public static function handle(
        string $preferredTextEditor,
        string $bodyTmpFilename,
        string $defaultBody = '',
    ): ?string {
        $sensibleEditorDefaults = config('editor.options');

        if ($preferredTextEditor == TextEditor::BUILTIN->value) {
            info("No worries, here's one for you.");

            $sensibleEditorDefaults = '';

            return textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                default: $defaultBody,
                rows: 25,
            );
        }

        pause('Are you ready to embark on this quest?');

        $bodyTmpFileIsSaved = false;
        $bodyTmpFileIsEmpty = true;

        Storage::put($bodyTmpFilename, $defaultBody);

        $bodyTmpFilePath = Storage::path($bodyTmpFilename);

        do {
            Process::forever()->tty()->run(
                "$preferredTextEditor '$sensibleEditorDefaults' $bodyTmpFilePath", // Remove these single quotes, and you'll hate your life
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
