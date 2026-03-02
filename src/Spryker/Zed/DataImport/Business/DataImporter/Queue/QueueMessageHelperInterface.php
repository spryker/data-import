<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;

interface QueueMessageHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return mixed|null
     */
    public function getDecodedMessageBody(QueueReceiveMessageTransfer $queueReceiveMessageTransfer);

    public function handleSuccessMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): QueueReceiveMessageTransfer;

    public function handleFailedMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer, string $errorMessage): QueueReceiveMessageTransfer;
}
