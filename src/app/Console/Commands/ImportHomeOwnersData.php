<?php

namespace App\Console\Commands;

use App\Services\NameParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportHomeOwnersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:homeowners {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import homeowners data from a CSV file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        retrieve the file name from the command line
        $file = $this->argument('file');
//        read the file into an array
        $data = array_map('str_getcsv', file($file));

//        flatten the array
        $data = array_merge(...$data);

        try {
            (new NameParser($data))->handle();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
