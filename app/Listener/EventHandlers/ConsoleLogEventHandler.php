<?php

namespace App\Listener\EventHandlers;

use App\Providers\EventLog\Facade\EventLog;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use \Exception;

/*
* ConsoleLogEventHandler
*/
class ConsoleLogEventHandler
{
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function logToConsole($tx_event)
    {
        if (!isset($GLOBALS['XCHAIN_GLOBAL_COUNTER'])) { $GLOBALS['XCHAIN_GLOBAL_COUNTER'] = 0; }
        $count = ++$GLOBALS['XCHAIN_GLOBAL_COUNTER'];

        $xcp_data = $tx_event['counterpartyTx'];
        if ($tx_event['network'] == 'counterparty') {
            if ($xcp_data['type'] == 'send') {
                $this->wlog("from: {$xcp_data['sources'][0]} to {$xcp_data['destinations'][0]}: {$xcp_data['quantity']} {$xcp_data['asset']} [{$tx_event['txid']}]");
            } else {
                $this->wlog("[".date("Y-m-d H:i:s")."] XCP TX FOUND: {$xcp_data['type']} at {$tx_event['txid']}");

            }
        } else {
            if (rand(1, 100) === 1) {
                $c = Carbon::createFromTimestampUTC(floor($tx_event['timestamp']))->timezone(new \DateTimeZone('America/Chicago'));
                $this->wlog("heard $count tx.  Last tx time: ".$c->format('Y-m-d h:i:s A T'));
            }
        }

        EventLog::increment('tx.received');

    }

    public function subscribe($events) {
        $events->listen('xchain.tx.received', 'App\Listener\EventHandlers\ConsoleLogEventHandler@logToConsole');
    }

    protected function wlog($text) {
        $this->log->info($text);
    }
}