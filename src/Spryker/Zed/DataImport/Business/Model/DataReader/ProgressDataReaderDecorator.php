<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader;

use Countable;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Communication\Console\ProgressBar\ProgressBarHelperInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressDataReaderDecorator implements DataReaderDecoratorInterface, ConfigurableDataReaderInterface, Countable
{
    protected DataReaderInterface $innerReader;

    protected ?OutputInterface $output = null;

    protected ?ProgressBar $progressBar = null;

    public function __construct(ProgressBarHelperInterface $progressBarHelper)
    {
        if ($progressBarHelper->getOutput() !== null) {
            $this->output = $progressBarHelper->getOutput();
        }
    }

    public function setInnerReader(DataReaderInterface $dataReader): void
    {
        $this->innerReader = $dataReader;
    }

    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    public function configure(DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer): self
    {
        if ($this->innerReader instanceof ConfigurableDataReaderInterface) {
            $this->innerReader->configure($dataImportReaderConfigurationTransfer);
        }

        return $this;
    }

    public function count(): int
    {
        if ($this->innerReader instanceof Countable) {
            return $this->innerReader->count();
        }

        return 0;
    }

    #[\ReturnTypeWillChange]
    public function current(): DataSetInterface
    {
        return $this->innerReader->current();
    }

    public function next(): void
    {
        $this->innerReader->next();
        $this->progressBar?->advance();
    }

    public function key(): int
    {
        return $this->innerReader->key();
    }

    public function valid(): bool
    {
        $valid = $this->innerReader->valid();

        if (!$valid) {
            $this->finishProgressBar();
        }

        return $valid;
    }

    public function rewind(): void
    {
        $this->innerReader->rewind();
        $this->initProgressBar();
        $this->innerReader->rewind();
    }

    protected function initProgressBar(): void
    {
        $max = $this->innerReader instanceof Countable ? $this->innerReader->count() : 0;

        $this->progressBar = new ProgressBar($this->output, $max);
        $this->progressBar->setFormat('   %current%/%max% [%bar%] %percent:3s%% | Elapsed: %elapsed:6s% | ETA: %estimated:-6s% | Memory: %memory:6s%');
        $this->progressBar->minSecondsBetweenRedraws(1);
        $this->progressBar->start();
    }

    protected function finishProgressBar(): void
    {
        if ($this->progressBar === null) {
            return;
        }

        $this->progressBar->finish();
        $this->output->writeln('');
        $this->progressBar = null;
    }
}
