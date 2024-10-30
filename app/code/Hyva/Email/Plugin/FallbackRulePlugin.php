<?php

declare(strict_types=1);

namespace Hyva\Email\Plugin;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\View\Design\Fallback\Rule\ModularSwitch as FallbackRule;

class FallbackRulePlugin
{
    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    public function __construct(
        ComponentRegistrarInterface $componentRegistrar
    ) {
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * Create a new fallback pattern with this module's web dir.
     *
     * This means that this modules email.less and other files are found when Magento is looking for global files
     * during static content deploy. There is no official way to overwrite theme files with module files.
     *
     * @param FallbackRule $subject
     * @param string[] $result
     * @param array $params
     * @return string[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetPatternDirs(FallbackRule $subject, array $result, array $params): array
    {
        if (!isset($params['module_name'])) {
            $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Hyva_Email');
            $result[] = $modulePath . '/view/frontend/web';
        }
        return $result;
    }
}
