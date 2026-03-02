<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

class DataSetWriterPersistenceStateRegistry
{
    /**
     * @var bool
     */
    protected static $isPersisted = true;

    public static function getIsPersisted(): bool
    {
        return static::$isPersisted;
    }

    public static function setIsPersisted(bool $isPersisted): void
    {
        static::$isPersisted = $isPersisted;
    }
}
