<?php

namespace Drupal\dtl_alice_fixtures\Alice;

use Nelmio\Alice\Generator\Instantiator\Chainable\AbstractChainableInstantiator;
use Nelmio\Alice\FixtureInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class EntityInstantiator extends AbstractChainableInstantiator
{
    /**
     * @var EntityTypeManagerInterface
     */
    private $typeManager;

    public function __construct(EntityTypeManagerInterface $typeManager)
    {
        $this->typeManager = $typeManager;
    }

    public function canInstantiate(FixtureInterface $fixture): bool
    {
        return $this->typeManager->hasHandler($fixture->getClassName(), 'storage');
    }

    /**
     * {@inheritDoc}
     */
    protected function createInstance(FixtureInterface $fixture)
    {
        $definition = $this->typeManager->getDefinition($fixture->getClassName());
        $class = $definition->getOriginalClass();
        $fields = [];

        foreach ($fixture->getSpecs()->getProperties() as $property) {
            if ($property->getName() === 'type') {
                $fields['type'] = $property->getValue();
            }
        }
        return $class::create($fields);
    }
}
