<?php

namespace Drupal\dtl_alice_fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
    protected function configure()
    {
        $this->setName('alice:load-fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World!!!');
    }
}
