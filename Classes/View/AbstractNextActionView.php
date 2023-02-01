<?php

declare(strict_types=1);

namespace DigiComp\NextActionViews\View;

use Neos\Flow\Mvc\View\AbstractView;

abstract class AbstractNextActionView extends AbstractView
{
    /**
     * @inheritDoc
     */
    protected $supportedOptions = [
        'action' => [null, 'Target action to redirect to', 'string'],
        'controller' => [null, 'Target controller', 'string'],
        'package' => [null, 'Target package', 'string'],
        'subPackage' => [null, 'Target sub package', 'string'],
        'arguments' => [[], 'Target arguments', 'array'],
        'format' => [null, 'Target format', 'string'],
    ];
}
