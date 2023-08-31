<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Rabsana\Trade\Contracts\Abstracts\Action;
use Rabsana\Trade\Models\SymbolInfo;
use Rabsana\Trade\Tasks\GetAllSymbolsTask;
use Illuminate\Support\Str;

class UpdateSymbolInfoAction extends Action
{
    public function run()
    {
        DB::beginTransaction();
        try {

            // get all symbols
            $symbols = app(GetAllSymbolsTask::class)->run();

            // get info table columns
            $attributes = Schema::getColumnListing((new SymbolInfo())->getTable());

            $updateInfo = [];

            foreach ($symbols as $symbol) {

                // get or create the info row for symbol
                $info = SymbolInfo::firstOrCreate([
                    'symbol_id' => $symbol->id
                ]);

                foreach ($attributes as $attribute) {

                    $attributeName = Str::ucfirst(Str::camel($attribute));
                    $taskName = "Rabsana\\Trade\\Tasks\\Info\\Get{$attributeName}Task";

                    if (!class_exists($taskName)) {
                        continue;
                    }

                    $updateInfo[$attribute] = (new $taskName())->run($symbol->pair);
                }

                // update symbol info
                SymbolInfo::whereId($info->id)->update($updateInfo);
            }

            DB::commit();

            return [
                'success'   => true,
                'message'   => 'Successfully updated'
            ];


            // 
        } catch (Exception $e) {

            DB::rollBack();

            Log::debug("rabsana-trade-updating-symbol-info-error" . $e);

            return [
                'success'   => false,
                'message'   => 'There is a problem in updating symbol info'
            ];
        }
    }
}
