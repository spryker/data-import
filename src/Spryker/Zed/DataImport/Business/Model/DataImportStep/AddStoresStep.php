<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface;

class AddStoresStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_STORES = 'stores';

    /**
     * @var array<string, int>
     */
    protected array $stores = [];

    public function __construct(protected DataImportToStoreFacadeInterface $storeFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->stores) {
            $storeTransfers = $this->storeFacade->getAllStores();

            foreach ($storeTransfers as $storeTransfer) {
                $this->stores[$storeTransfer->getName()] = $storeTransfer->getIdStore();
            }
        }

        $dataSet[static::KEY_STORES] = $this->stores;
    }
}
