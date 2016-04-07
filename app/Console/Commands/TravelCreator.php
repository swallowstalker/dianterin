<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TravelCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'travel:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create travel for today.';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
