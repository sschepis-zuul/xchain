<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // core xchain commands
        \App\Console\Commands\Blocks\LoadMissingBlocksCommand::class,
        \App\Console\Commands\Blocks\ReparseBlockCommand::class,
        \App\Console\Commands\Transaction\ResendTransactionNotificationsCommand::class,
        \App\Console\Commands\Transaction\ReprocessAllNotificationsCommand::class,
        \App\Console\Commands\Transaction\MultiInputSweepCommand::class,

        // development
        \App\Console\Commands\Development\PopulateNotificationCommand::class,
        \App\Console\Commands\Development\SendManualNotificationCommand::class,
        \App\Console\Commands\Development\ExportWIFCommand::class,
        \App\Console\Commands\Development\ShowEnhancedBitcoinTransactionCommand::class,
        \App\Console\Commands\Development\ShowRawBitcoinTransactionCommand::class,
        \App\Console\Commands\Development\ReindexTransactionAddressesCommand::class,
        \App\Console\Commands\Development\ShowParsedBitcoinTransactionCommand::class,
        \App\Console\Commands\Development\AssetInfoCacheCommand::class,

        // Other
        \App\Console\Commands\Experiment\ExperimentCommand::class,

        // prune
        \App\Console\Commands\Prune\PruneTransactionsCommand::class,
        \App\Console\Commands\Prune\PruneBlocksCommand::class,

        // accounts
        \App\Console\Commands\Accounts\SweepAccountCommand::class,
        \App\Console\Commands\Accounts\ShowAccountsCommand::class,
        \App\Console\Commands\Accounts\ReconcileAccountsCommand::class,
        \App\Console\Commands\Accounts\ForceSyncAddressAccountsCommand::class,
        \App\Console\Commands\Accounts\CloseAccountCommand::class,
        \App\Console\Commands\Accounts\BalanceLedgerCommand::class,
        \App\Console\Commands\Accounts\ExpirePendingTransactionsCommand::class,
        

        // address
        \App\Console\Commands\Address\FindAddressCommand::class,
        \App\Console\Commands\Address\ResurrectAddressCommand::class,

        // TXOs
        \App\Console\Commands\TXO\ReconcileTXOsCommand::class,
        \App\Console\Commands\TXO\ShowTXOsCommand::class,
        \App\Console\Commands\TXO\PruneSpentUTXOs::class,
        \App\Console\Commands\TXO\TXOsCacheCommand::class,

        // vendor commands
        \Tokenly\ConsulHealthDaemon\Console\ConsulHealthMonitorCommand::class,

        // API Provider commands
        \Tokenly\LaravelApiProvider\Commands\MakeAPIModelCommand::class,
        \Tokenly\LaravelApiProvider\Commands\MakeAPIRespositoryCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('xchain:prune-transactions')->daily();
        $schedule->command('xchain:prune-blocks')->daily();

        // $schedule->command('xchain:reconcile-accounts -l -e devon@tokenly.com')->cron('15 */4 * * * *')->withoutOverlapping();

        // 5:25 AM CDT
        $schedule->command('xchain:reconcile-accounts -a -l -e devon@tokenly.com')->cron('25 10 * * * *')->withoutOverlapping();
    }

}
