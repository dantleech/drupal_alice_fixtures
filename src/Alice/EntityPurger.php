<?php

namespace Drupal\drupal_alice_fixtures\Alice;

use Nelmio\Alice\ObjectSet;

class EntityPurger
{
    public function purge(ObjectSet $objectSet)
    {
        $entityTypes = [];
        foreach ($objectSet->getObjects() as $object) {
            $entityTypes[$object->getEntityTypeId()] = true;
        }

        foreach (array_keys($entityTypes) as $entityType) {
            $result = \Drupal::entityQuery($entityType)
                ->execute();

            $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
            $entities = $storage_handler->loadMultiple($result);
            $storage_handler->delete($entities);
        }
    }
}
