<?php

namespace Rabsana\Trade\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Contracts\Interfaces\Trade;

class MatchOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:match {number=100} {pair=NULL} {--sleep=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command match buy and sell orders";



    public function handle()
    {
        if(!modulesConfig('modules.p2p_mode')) {
            return 0;
        }

        $result = app(Trade::class)->match(
            $this->argument('number'),
            $this->argument('pair')
        );


        $this->info($result['message']);
        $this->info("The orders matched in {$result['duration']} seconds");
        $this->info("The status of {$result['ordersFilling']} orders changed to FILLING");
        $this->info("The status of {$result['ordersFilled']} orders changed to FILLED");

        sleep($this->option('sleep'));
    }
}
