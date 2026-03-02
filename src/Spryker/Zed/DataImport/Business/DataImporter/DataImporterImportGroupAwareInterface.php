<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter;

interface DataImporterImportGroupAwareInterface
{
    public function setImportGroup(string $importGroup): void;

    public function getImportGroup(): string;
}
