<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

trait DataSetWriterPersistenceStateAwareTrait
{
    protected function isDataSetWriterDataPersisted(): bool
    {
        return DataSetWriterPersistenceStateRegistry::getIsPersisted();
    }

    protected function setDataSetWriterPersistenceState(bool $isPersisted): void
    {
        DataSetWriterPersistenceStateRegistry::setIsPersisted($isPersisted);
    }
}
