<?php

namespace Drupal\dtl_alice_fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Nelmio\Alice\Loader\NativeLoader;
use Drupal\dtl_alice_fixtures\Alice\DrupalLoader;

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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new DrupalLoader($this->typeManager);
        $objectSet = $loader->loadData([
            'node' => [
                'article{1..10}' => [
                    'type' => 'article',
                    'field_body' => '<text()>',
                ],
            ],
        ]);
    }
}
