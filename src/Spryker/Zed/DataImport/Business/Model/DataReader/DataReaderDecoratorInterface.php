<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader;

use Symfony\Component\Console\Output\OutputInterface;

interface DataReaderDecoratorInterface extends DataReaderInterface
{
    public function setInnerReader(DataReaderInterface $dataReader): void;

    public function getOutput(): ?OutputInterface;
}
