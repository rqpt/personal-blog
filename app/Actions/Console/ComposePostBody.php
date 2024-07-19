<?php

namespace App\Actions\Console;

use Illuminate\Support\Facades\{Process, Storage};

use function Laravel\Prompts\{pause, textarea};

class ComposePostBody
{
    public static function handle(
        string $preferredTextEditor,
        string $storageLocation,
    ): string {
        if ($preferredTextEditor == 'builtin') {
            info("No worries, here's one for you.");

            return textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                default: Storage::fileExists($storageLocation)
                    ? Storage::get($storageLocation)
                    : '',
                rows: 25,
            );
        }

        pause('Are you ready to embark on this quest?');

        $localBackupIsSaved = false;
        $localBackupIsEmpty = true;

        $fullDraftPath = Storage::path($storageLocation);

        do {
            Process::forever()->tty()->run(
                "$preferredTextEditor $fullDraftPath",
            );

            $localBackupIsSaved = Storage::exists($storageLocation);

            $localBackupIsEmpty = $localBackupIsSaved
                && Storage::size($storageLocation) == 0;
        } while (!$localBackupIsSaved || $localBackupIsEmpty);

        return Storage::get($storageLocation);
    }
}
