<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\View\Dto;

use Neos\Eel\CompilingEvaluator;
use Neos\Eel\Context;
use Neos\Flow\Mvc\Controller\ControllerContext;

/**
 * @internal
 */
class TargetActionDto
{
    /**
     * @var string
     */
    protected string $actionName;

    /**
     * @var string
     */
    protected string $controllerName;

    /**
     * @var string
     */
    protected string $packageKey;

    /**
     * @var string|null
     */
    protected ?string $subPackageKey = null;

    /**
     * @var array
     */
    protected array $arguments = [];

    /**
     * @var string
     */
    protected string $format;

    /**
     * @var ControllerContext
     */
    protected ControllerContext $controllerContext;

    public function __construct(ControllerContext $controllerContext, array $arguments)
    {
        $this->controllerContext = $controllerContext;
        $this->actionName = $arguments['action'] ?? $controllerContext->getRequest()->getControllerActionName();
        $this->controllerName = $arguments['controller'] ?? $controllerContext->getRequest()->getControllerName();
        $this->packageKey = $arguments['package'] ?? $controllerContext->getRequest()->getControllerPackageKey();
        $this->subPackageKey = $arguments['subPackage'] ?? $controllerContext->getRequest()->getControllerSubpackageKey();
        $this->arguments = $arguments['arguments'] ?? [];
        $this->format = $arguments['format'] ?? $controllerContext->getRequest()->getFormat();

        $context = new Context([
            'getContextArgument' => function (string $argument): object {
                return $this->controllerContext->getArguments()->getArgument($argument)->getValue();
            },
        ]);
        $evaluator = new CompilingEvaluator();
        foreach ($this->arguments as &$argument) {
            $argument = $evaluator->evaluate($argument, $context);
        }
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getPackageKey(): string
    {
        return $this->packageKey;
    }

    public function getSubPackageKey(): ?string
    {
        return $this->subPackageKey;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
