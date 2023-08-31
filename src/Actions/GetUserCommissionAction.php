<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Rabsana\Trade\Contracts\Abstracts\Action;
use Rabsana\Trade\Tasks\GetAllCommissionsTask;
use Illuminate\Support\Str;
use Rabsana\Trade\Helpers\Math;

class GetUserCommissionAction extends Action
{
    public $name = '';
    public $takerFee = 0;
    public $makerFee = 0;

    public function run($user = null)
    {
        $user = $user ?? request()->user();
        try {
            // get all commissions
            $commissions = app(GetAllCommissionsTask::class)->run();

            // get all condition property amount and store it in properties array
            $properties = [];
            foreach ($commissions as $commission) {
                foreach ($commission->conditions as $condition) {

                    // create property key with property name and period time
                    $propertyKey = $condition->property . $condition->period;

                    // check if the property has already fetched to pervent n + 1 problem
                    if (isset($properties[$propertyKey])) {
                        continue;
                    }

                    // create task namespace
                    $task = "Rabsana\\Trade\\Tasks\\Get" . Str::ucfirst($condition->property) . "PropertyTask";

                    // pass commission and condition to the task
                    $properties[$propertyKey] = (new $task())->run($commission, $condition, $user);
                }
            }

            // try to find commission
            foreach ($commissions as $commission) {

                $conditionPassed = true;

                foreach ($commission->conditions as $condition) {

                    // create property key with property name and period time
                    $propertyKey = $condition->property . $condition->period;

                    // get the property
                    $property = $properties[$propertyKey];


                    if (!(Math::instance())->{$condition->operator}($property, $condition->operand)) {
                        $conditionPassed = false;
                    }
                }

                if ($conditionPassed) {
                    $this->name = $commission->name;
                    $this->takerFee = Math::number((float)$commission->taker_fee);
                    $this->makerFee = Math::number((float)$commission->maker_fee);
                }
            }

            return $this->response();


            // 
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    public function response()
    {
        return [
            'name'      => $this->name,
            'takerFee'  => $this->takerFee,
            'makerFee'  => $this->makerFee,
        ];
    }
}
