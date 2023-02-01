<?php

namespace DigiComp\NextActionViews\Tests\Functional;

use Neos\Flow\Tests\Functional\Persistence\Fixtures\TestEntity;
use Neos\Flow\Tests\FunctionalTestCase;
use Psr\Http\Message\ServerRequestFactoryInterface;

abstract class AbstractNextActionViewFunctionalTestcase extends FunctionalTestCase
{
    /**
     * @inheritDoc
     */
    protected static $testablePersistenceEnabled = true;

    /**
     * @var ServerRequestFactoryInterface
     */
    protected ServerRequestFactoryInterface $serverRequestFactory;

    /**
     * Additional setup: Routes
     */
    protected function setUp(): void
    {
        parent::setUp();

        $route = $this->registerRoute('test with object', 'test/{@controller}/{@action}/{entity}', [
            '@package' => 'DigiComp.NextActionViews',
            '@subpackage' => 'Tests\Functional\Fixtures',
            '@format' => 'html',
        ]);
        $route->setRoutePartsConfiguration([
            'entity' => [
                'objectType' => TestEntity::class,
            ],
        ]);
        $this->registerRoute('test', 'test/{@controller}/{@action}', [
            '@package' => 'DigiComp.NextActionViews',
            '@subpackage' => 'Tests\Functional\Fixtures',
            '@format' => 'html',
        ]);

        $this->serverRequestFactory = $this->objectManager->get(ServerRequestFactoryInterface::class);
    }
}
