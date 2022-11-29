<?php

namespace App\Console\Commands;

class Install extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {--prod}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database and seed data';

    /**
     * Command to run
     * @return void
     */
    protected function start()
    {
        if (!file_exists('public/storage')) {
            $this->call('storage:link');
        }
        
        $this->call('migrate:fresh');

        if ($this->option('prod')) {
            $this->call('db:seed');
        } else {
            $this->call('db:seed', [
                '--class' => 'SampleDatabaseSeeder',
            ]);
        }

        $this->call('tnt:refresh');
        $this->call('jwt:secret');

        if (!config('app.key')) {
            $this->call('key:generate');
        }
    }
}
