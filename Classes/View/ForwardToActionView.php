<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\View;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Exception as MvcException;
use Neos\Flow\Mvc\Exception\ForwardException;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Persistence\Exception\UnknownObjectException;
use Neos\Flow\Persistence\PersistenceManagerInterface;

class ForwardToActionView extends AbstractNextActionView
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @throws MvcException
     * @throws ForwardException
     * @throws StopActionException
     * @throws UnknownObjectException
     */
    public function render(): void
    {
        $this->forward(new Dto\TargetActionDto($this->controllerContext, $this->options));
    }

    /**
     * @param Dto\TargetActionDto $targetActionDto
     *
     * @throws MvcException\InvalidActionNameException
     * @throws MvcException\InvalidArgumentNameException
     * @throws MvcException\InvalidArgumentTypeException
     * @throws MvcException\InvalidControllerNameException
     * @throws UnknownObjectException
     * @throws ForwardException
     */
    protected function forward(Dto\TargetActionDto $targetActionDto): void
    {
        $nextRequest = clone $this->controllerContext->getRequest();
        $nextRequest->setControllerActionName($targetActionDto->getActionName());
        $nextRequest->setControllerName($targetActionDto->getControllerName());
        $nextRequest->setControllerPackageKey($targetActionDto->getPackageKey());
        $nextRequest->setControllerSubpackageKey($targetActionDto->getSubPackageKey());
        $nextRequest->setFormat($targetActionDto->getFormat());

        $regularArguments = [];
        foreach ($targetActionDto->getArguments() as $argumentName => $argumentValue) {
            if (\str_starts_with($argumentName, '__')) {
                $nextRequest->setArgument($argumentName, $argumentValue);
            } else {
                $regularArguments[$argumentName] = $argumentValue;
            }
        }
        $nextRequest->setArguments($this->persistenceManager->convertObjectsToIdentityArrays($regularArguments));

        $forwardException = new ForwardException();
        $forwardException->setNextRequest($nextRequest);
        throw $forwardException;
    }
}
