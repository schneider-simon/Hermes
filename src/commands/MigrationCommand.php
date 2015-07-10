<?php
use Illuminate\Console\Command;
use Illuminate\Container\Container;

class MigrationCommand extends Command{
    /**
    * Name of the command.
    *
    * @param string
    */
    protected $name = 'hermes:migrate';

    /**
     * @param string
     */
    protected $description = 'Migrate the necessary package migration files for hermes (messages, conversations, conversation_user, message_states).';

    /**
     * Run the package migrations
     */
    public function handle()
    {
        $migrations = $this->app->make('migration.repository');
        $migrations->createRepository();

        $migrator = $this->app->make('migrator');
        $migrator->run(__DIR__.'/../src/migrations');
        $migrator->run(__DIR__.'/Fixtures/Migrations');
    }
} 