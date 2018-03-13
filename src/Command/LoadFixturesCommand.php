<?php

namespace Drupal\drupal_alice_fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Nelmio\Alice\Loader\NativeLoader;
use Drupal\drupal_alice_fixtures\Alice\DrupalLoader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Nelmio\Alice\ObjectSet;
use Nelmio\Alice\ObjectBag;
use Nelmio\Alice\ParameterBag;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Drupal\drupal_alice_fixtures\Alice\EntityPurger;

class LoadFixturesCommand extends Command
{
    /**
     * @var EntityTypeManagerInterface
     */
    private $typeManager;

    public function __construct(EntityTypeManagerInterface $typeManager = null)
    {
        parent::__construct();
        $this->typeManager = $typeManager;
    }

    protected function configure()
    {
        $this->setName('alice:load-fixtures');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path to load fixtures from');
        $this->addOption('purge', null, InputOption::VALUE_NONE, 'Purge existing entities before loading the fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $doPurge = $input->getOption('purge');

        $start = microtime(true);
        $output->writeln('Loading fixtures');

        $loader = new DrupalLoader($this->typeManager);
        $objectSet = new ObjectSet(new ParameterBag(), new ObjectBag());
        foreach ($this->fixtureFiles($path) as $file) {
            $output->writeln('File: ' . $file);
            $objectSet = $loader->loadFile($file, $objectSet->getParameters(), $objectSet->getObjects());
        }

        if ($doPurge) {
            (new EntityPurger())->purge($objectSet);
        }


        foreach ($objectSet->getObjects() as $object) {
            $object->save();
            $output->write('.');
        }

        $output->writeln(PHP_EOL);
        $output->writeln(sprintf(
            'Loaded fixtures in %ss',
            number_format(microtime(true) - $start, 2)
        )); 
    }

    private function fixtureFiles(string $path): array
    {
        if (false === file_exists($path)) {
            throw new InvalidArgumentException(sprintf(
                'File "%s" does not exist',
                $path
            ));
        }

        if (is_file($path)) {
            return [ $path ];
        }

        $files = Finder::create()
            ->in($path)
            ->name('*.yml')
            ->name('*.php')
            ->files();

        return iterator_to_array($files);
    }
}
