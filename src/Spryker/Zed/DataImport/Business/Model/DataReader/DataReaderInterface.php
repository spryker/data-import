<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader;

use Iterator;

/**
 * @extends \Iterator<\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface>
 */
interface DataReaderInterface extends Iterator
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    #[\ReturnTypeWillChange]
    public function current();
}
