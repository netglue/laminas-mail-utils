<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

interface KeyValueMetadata
{
    /**
     * Return key:value metadata for the email message
     *
     * @return mixed[]
     */
    public function getMetaData() : iterable;
}
