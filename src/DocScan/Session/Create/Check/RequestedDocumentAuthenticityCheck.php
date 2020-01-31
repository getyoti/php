<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedDocumentAuthenticityCheck extends RequestedCheck
{

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return Constants::ID_DOCUMENT_AUTHENTICITY;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return null;
    }
}
