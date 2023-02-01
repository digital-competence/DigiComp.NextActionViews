<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\Tests\Functional;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Tests\Functional\Persistence\Fixtures\TestEntity;

class ForwardToActionViewTest extends AbstractNextActionViewFunctionalTestcase
{
    /**
     * @test
     */
    public function itIssuesAForwardResponse(): void
    {
        $request = $this->serverRequestFactory
            ->createServerRequest('GET', new Uri('http://localhost/test/forward/source'));
        $response = $this->browser->sendRequest($request);

        self::assertStringContainsString('TARGET', $response->getBody()->getContents());

        $entity = new TestEntity();
        $entity->setName('Foo');
        $this->persistenceManager->add($entity);
        $this->persistenceManager->persistAll();
        $identifier = $this->persistenceManager->getIdentifierByObject($entity);

        $request = $this->serverRequestFactory
            ->createServerRequest('GET', new Uri('http://localhost/test/forward/sourceWithObject/' . $identifier));
        $response = $this->browser->sendRequest($request);
        $content = $response->getBody()->getContents();

        self::assertStringContainsString('TARGET', $content);
        self::assertStringContainsString($identifier, $content);
    }
}
