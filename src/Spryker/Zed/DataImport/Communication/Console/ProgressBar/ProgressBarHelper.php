<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\DataImport\Communication\Console\ProgressBar;

use Symfony\Component\Console\Output\OutputInterface;

class ProgressBarHelper implements ProgressBarHelperInterface
{
    protected static ?OutputInterface $output = null;

    public function getOutput(): ?OutputInterface
    {
        return static::$output;
    }

    public function setOutput(OutputInterface $output): void
    {
        static::$output = $output;
    }
}
