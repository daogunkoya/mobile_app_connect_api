<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Actions\Bank\BanksSyncService;

class SyncBanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banks:sync';

    protected $bankSyncService;

    /**
     * The console command description.
     *
     * @var string
     */
   // The console command description
   protected $description = 'Sync bank data from the external API into the database';

    /**
     * Execute the console command.
     */

      public function __construct(BanksSyncService $bankSyncService)
    {
        parent::__construct();
        $this->bankSyncService = $bankSyncService;
    }
    public function handle():void
    {
        try {
            $this->info('Starting bank sync...');
            $this->bankSyncService->syncBankData();
            $this->info('Bank sync completed successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred during bank sync: ' . $e->getMessage());
        }
    }
}
