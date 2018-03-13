<?php

namespace Drupal\drupal_alice_fixtures\Alice;

use Nelmio\Alice\Generator\Instantiator\Chainable\AbstractChainableInstantiator;
use Nelmio\Alice\FixtureInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Generator\InstantiatorInterface;
use Nelmio\Alice\Generator\Instantiator\ExistingInstanceInstantiator;
use Nelmio\Alice\Generator\Instantiator\InstantiatorResolver;
use Nelmio\Alice\Generator\Instantiator\InstantiatorRegistry;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class DrupalLoader extends NativeLoader
{
    /**
     * @var EntityTypeManagerInterface
     */
    private $typeManager;

    public function __construct(EntityTypeManagerInterface $typeManager, FakerGenerator $fakerGenerator = null)
    {
        $this->typeManager = $typeManager;
        parent::__construct($fakerGenerator);
    }

    protected function createInstantiator(): InstantiatorInterface
    {
        return new ExistingInstanceInstantiator(
            new InstantiatorResolver(
                new InstantiatorRegistry([
                    new EntityInstantiator($this->typeManager)
                ])
            )
        );
    }
}
