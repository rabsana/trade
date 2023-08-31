<?php

namespace Rabsana\Trade\Console\Commands;

use Illuminate\Console\Command;
use Rabsana\Trade\Actions\UpdateSymbolInfoAction;

class SymbolInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symbol:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command store symbol info data like price, volume, high and low price,...";



    public function handle()
    {
        $res = app(UpdateSymbolInfoAction::class)->run();

        if ($res['success']) {
            $this->info($res['message']);
        } else {
            $this->error($res['message']);
        }
    }
}
