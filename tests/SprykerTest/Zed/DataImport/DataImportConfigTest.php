<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport;

use Codeception\Test\Unit;
use Spryker\Zed\DataImport\DataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group DataImportConfigTest
 * Add your own group annotations below this line
 */
class DataImportConfigTest extends Unit
{
    protected const string FILE_NAME = 'src/Spryker/StoreDataImport/data/import/store.csv';

    protected const string PATH_OUTSIDE_APPLICATION_ROOT = '/tmp/import/store.csv';

    protected readonly DataImportBusinessTester $tester;

    public function testGetApplicationRootRelativePathStripsTheApplicationRootFromAnAbsolutePath(): void
    {
        $dataImportConfig = new DataImportConfig();

        $absolutePath = $dataImportConfig->getApplicationRootPath() . static::FILE_NAME;

        $this->assertSame(static::FILE_NAME, $dataImportConfig->getApplicationRootRelativePath($absolutePath));
    }

    public function testGetApplicationRootRelativePathKeepsAPathThatIsAlreadyRelative(): void
    {
        $dataImportConfig = new DataImportConfig();

        $this->assertSame(static::FILE_NAME, $dataImportConfig->getApplicationRootRelativePath(static::FILE_NAME));
    }

    public function testGetApplicationRootRelativePathKeepsAPathOutsideTheApplicationRoot(): void
    {
        $dataImportConfig = new DataImportConfig();

        $this->assertSame(
            static::PATH_OUTSIDE_APPLICATION_ROOT,
            $dataImportConfig->getApplicationRootRelativePath(static::PATH_OUTSIDE_APPLICATION_ROOT),
        );
    }

    public function testGetApplicationRootRelativePathStripsOnlyTheLeadingApplicationRoot(): void
    {
        // The application root directory name occurs again inside the module path, and must not be stripped there.
        $dataImportConfig = new DataImportConfig();

        $fileName = 'src/Spryker/StoreDataImport/data/import/store.csv';
        $absolutePath = $dataImportConfig->getApplicationRootPath() . $fileName;

        $this->assertSame($fileName, $dataImportConfig->getApplicationRootRelativePath($absolutePath));
    }
}
