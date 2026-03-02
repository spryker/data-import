<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Exception;

use Exception;
use Generated\Shared\Transfer\ErrorTransfer;

class DataImportException extends Exception
{
    /**
     * @var \Generated\Shared\Transfer\ErrorTransfer|null
     */
    protected ?ErrorTransfer $errorTransfer = null;

    public function setError(ErrorTransfer $errorTransfer): void
    {
        $this->errorTransfer = $errorTransfer;
    }

    public function findError(): ?ErrorTransfer
    {
        return $this->errorTransfer;
    }
}
