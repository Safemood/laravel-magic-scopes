<?php

namespace Safemood\MagicScopes\Commands;

use Illuminate\Console\Command;

class MagicScopeCommand extends Command
{
    public $signature = 'laravel-magic-scopes';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
