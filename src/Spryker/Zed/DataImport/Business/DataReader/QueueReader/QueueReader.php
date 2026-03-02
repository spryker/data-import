<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataReader\QueueReader;

use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;

class QueueReader implements DataReaderInterface
{
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected $messages = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected $dataSet;

    /**
     * @var \Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer
     */
    protected $queueReaderConfigurationTransfer;

    public function __construct(
        DataImportToQueueClientInterface $queueClient,
        DataSetInterface $dataSet,
        DataImporterQueueReaderConfigurationTransfer $queueReaderConfigurationTransfer
    ) {
        $this->queueClient = $queueClient;
        $this->dataSet = $dataSet;
        $this->queueReaderConfigurationTransfer = $queueReaderConfigurationTransfer;
    }

    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->position;
    }

    public function valid(): bool
    {
        if (!isset($this->messages[$this->position])) {
            $this->readFromQueue();
        }

        return isset($this->messages[$this->position]);
    }

    public function rewind(): void
    {
        $this->readFromQueue();
    }

    public function current(): DataSetInterface
    {
        $currentMessage = $this->messages[$this->position];
        $this->dataSet->exchangeArray($currentMessage->toArray());

        return $this->dataSet;
    }

    protected function readFromQueue(): void
    {
        $this->messages = [];
        $this->position = 0;
        $newChunk = $this->queueClient->receiveMessages($this->getQueueName(), $this->getChunkSize(), $this->getQueueConsumerOptions());

        if (!count($newChunk)) {
            return;
        }

        $this->messages = $newChunk;
    }

    protected function getChunkSize(): ?int
    {
        return $this->queueReaderConfigurationTransfer->getChunkSize();
    }

    protected function getQueueName(): ?string
    {
        return $this->queueReaderConfigurationTransfer->getQueueName();
    }

    protected function getQueueConsumerOptions(): array
    {
        return $this->queueReaderConfigurationTransfer->getQueueConsumerOptions();
    }
}
