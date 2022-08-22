<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Periods;
use App\Models\Pricebedrooms;

class RemoveOldPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove-old-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove-old-periods';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $periods = Periods::whereDate('end_date', '<', Carbon::today())->get();
        $roomperiods = Pricebedrooms::whereDate('end', '<', Carbon::today())->get();

        foreach ($periods as $p) {
            $p->delete();
        }
        foreach ($roomperiods as $rp) {
            $rp->delete();
        }

        return Command::SUCCESS;
    }
}
