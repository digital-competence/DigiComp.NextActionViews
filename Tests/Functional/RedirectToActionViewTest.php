<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\Tests\Functional;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Tests\Functional\Persistence\Fixtures\TestEntity;

class RedirectToActionViewTest extends AbstractNextActionViewFunctionalTestcase
{
    /**
     * @test
     */
    public function itIssuesARedirectResponse(): void
    {
        $request = $this->serverRequestFactory
            ->createServerRequest('GET', new Uri('http://localhost/test/redirect/source'));
        $response = $this->browser->sendRequest($request);

        $redirect = $response->getHeaderLine('Location');
        self::assertNotEmpty($redirect);

        $entity = new TestEntity();
        $entity->setName('Foo');
        $this->persistenceManager->add($entity);
        $this->persistenceManager->persistAll();
        $identifier = $this->persistenceManager->getIdentifierByObject($entity);

        $request = $this->serverRequestFactory
            ->createServerRequest('GET', new Uri('http://localhost/test/redirect/sourceWithObject/' . $identifier));
        $response = $this->browser->sendRequest($request);

        $redirect = $response->getHeaderLine('Location');

        self::assertNotEmpty($redirect);
        self::assertStringContainsString($identifier, $redirect);
    }
}
