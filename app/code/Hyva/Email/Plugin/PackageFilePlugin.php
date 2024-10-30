<?php

declare(strict_types=1);

namespace Hyva\Email\Plugin;

use Magento\Deploy\Package\Package;
use Magento\Deploy\Package\PackageFile;

class PackageFilePlugin
{
    /**
     * Handle all static files from this module as if they were in global scope.
     * This affects static content deployment.
     *
     * @param PackageFile $subject
     * @param Package $package
     * @return array
     */
    public function beforeSetPackage(PackageFile $subject, Package $package)
    {
        if ($subject->getModule() == 'Hyva_Email') {
            $subject->setModule('');
        }
        return [$package];
    }
}
