<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\View;

use DigiComp\NextActionViews\View\Dto\TargetActionDto;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Http\Exception as HttpException;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Exception as MvcException;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Mvc\Routing\UriBuilder;

class RedirectToActionView extends AbstractNextActionView
{
    /**
     * @throws MvcException
     * @throws StopActionException
     * @throws \JsonException
     * @throws HttpException
     */
    public function render(): string
    {
        // Is there a better way to handle different formats?
        if ($this->controllerContext->getRequest()->getFormat() === 'modal.html') {
            $this->controllerContext->getResponse()->setContentType('application/json');
            return \json_encode(['redirectTarget' => $this->getRedirectTarget()], \JSON_THROW_ON_ERROR);
        }

        $response = $this->controllerContext->getResponse();
        $response->setRedirectUri(new Uri($this->getRedirectTarget()));
        throw new StopActionException();
    }

    /**
     * @return string
     * @throws MvcException
     * @throws HttpException
     */
    protected function getRedirectTarget(): string
    {
        $targetActionDto = new TargetActionDto($this->controllerContext, $this->options);
        $uriBuilder = new UriBuilder();
        $request = $this->controllerContext->getRequest();
        if (!($request instanceof ActionRequest)) {
            throw new MvcException(
                'Redirect View in controller "mode" can only redirect action requests',
                1399145964
            );
        }
        $uriBuilder->setRequest($request);
        $uriBuilder->setFormat($targetActionDto->getFormat());

        return $uriBuilder
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                $targetActionDto->getActionName(),
                $targetActionDto->getArguments(),
                $targetActionDto->getControllerName(),
                $targetActionDto->getPackageKey(),
                $targetActionDto->getSubPackageKey()
            );
    }
}
