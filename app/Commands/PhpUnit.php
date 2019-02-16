<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\ArgvInput;

class PhpUnit extends Command
{
    /**
     * @var string
     */
    protected $filename = 'docker-compose.yml';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $name = 'phpunit';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpunit command on a ephemeral test container.';


    public function __construct()
    {
        parent::__construct();
        $this->ignoreValidationErrors();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('env')) {
            $this->filename = "docker-compose.{$this->option('env')}.yml";
        }

        if (!file_exists(getcwd() . '/' . $this->filename)) {
            $this->error("{$this->filename} does not exist!");
            die;
        }

        $command = $this->input->__toString();
        $output = [];

        exec("docker-compose -f {$this->filename} run --rm -w /var/www/html test vendor/bin/{$command}", $output);

        $this->getOutput()->write(implode("\n", $output));
    }
}