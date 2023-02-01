<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\Tests\Functional\Fixtures\Controller;

use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Tests\Functional\Persistence\Fixtures\TestEntity;

abstract class AbstractNextActionController extends ActionController
{
    public function sourceAction()
    {
    }

    public function sourceWithObjectAction(TestEntity $entity)
    {
    }

    public function targetAction(?TestEntity $entity = null)
    {
        if ($entity) {
            return 'TARGET: ' . $this->persistenceManager->getIdentifierByObject($entity);
        }
        return 'TARGET';
    }
}
