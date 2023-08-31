<?php

namespace Rabsana\Trade\Console\Commands;

use Illuminate\Console\Command;
use Rabsana\Trade\Actions\UpdateSymbolChartAction;
use Rabsana\Trade\Actions\SeedSymbolChartAction;

class SymbolChartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symbol:chart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command store symbol data for drawing chart base on the filled orders";



    public function handle()
    {
        // first create symbol chart rows base on the candles
        app(SeedSymbolChartAction::class)->run();

        // update rows with filled orders
        app(UpdateSymbolChartAction::class)->run();
    }
}
