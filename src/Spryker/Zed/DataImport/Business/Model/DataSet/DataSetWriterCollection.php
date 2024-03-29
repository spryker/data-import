<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use Generated\Shared\Transfer\DataSetItemTransfer;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginApplicableAwareInterface;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface;

class DataSetWriterCollection implements DataSetWriterInterface
{
    /**
     * @var array<\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface>
     */
    protected $dataSetWriters = [];

    /**
     * @param array<\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface> $dataSetWriter
     */
    public function __construct(array $dataSetWriter)
    {
        $this->dataSetWriters = $dataSetWriter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        foreach ($this->getDatasetWriters() as $dataSetWriter) {
            /*
             * This check was added because of BC and will be removed once the
             * `\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface` is removed.
             */
            if ($dataSetWriter instanceof DataSetWriterPluginInterface) {
                /** @phpstan-var \Generated\Shared\Transfer\DataSetItemTransfer $dataSet */
                $dataSetWriter->write($dataSet);

                continue;
            }

            /** @phpstan-var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet */
            $dataSetItemTransfer = $this->mapDataSetToDataSetItemTransfer($dataSet);
            $dataSetWriter->write($dataSetItemTransfer);
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach ($this->getDatasetWriters() as $dataSetWriter) {
            $dataSetWriter->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\DataSetItemTransfer
     */
    protected function mapDataSetToDataSetItemTransfer(DataSetInterface $dataSet): DataSetItemTransfer
    {
        return (new DataSetItemTransfer())->setPayload(
            $dataSet->getArrayCopy(),
        );
    }

    /**
     * Generates DatasetWritersPlugins that are matching conditions.
     *
     * @return \Generator<\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface|null>
     */
    protected function getDatasetWriters()
    {
        /**
         * @var \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginApplicableAwareInterface $dataSetWriter
         */
        foreach ($this->dataSetWriters as $dataSetWriter) {
            if (
                !$dataSetWriter instanceof DataSetWriterPluginApplicableAwareInterface
                || $dataSetWriter->isApplicable()
            ) {
                /** @phpstan-var \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface $dataSetWriter */
                yield $dataSetWriter;
            }
        }
    }
}
