<?php

namespace App\Actions\Console;

use Illuminate\Support\Facades\{Process, Storage};

use function Laravel\Prompts\{pause, textarea};

class ComposePostBody
{
    public static function handle(
        string $preferredTextEditor,
        string $storageLocation,
    ): void {
        if ($preferredTextEditor == 'builtin') {
            info("No worries, here's one for you.");

            $body = textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                default: Storage::disk('backup')->get($storageLocation) ?? '',
                rows: 25,
            );

            Storage::disk('backup')->put($storageLocation, $body);
        } else {
            info("We won't see you again before you return to us with some content.");
            pause('Are you ready to embark on this quest?');

            info('Good. Safe travels!');

            $localBackupIsSaved = false;
            $localBackupIsEmpty = true;

            $fullDraftPath = Storage::disk('backup')
                ->path($storageLocation);

            do {
                Process::forever()->tty()->run(
                    "$preferredTextEditor $fullDraftPath",
                );

                $localBackupIsSaved = Storage::disk('backup')
                    ->exists($storageLocation);

                $localBackupIsEmpty = $localBackupIsSaved
                    && Storage::disk('backup')
                    ->size($storageLocation) == 0;
            } while (!$localBackupIsSaved || $localBackupIsEmpty);
        }
    }
}
