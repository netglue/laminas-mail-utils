<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

interface MultipleTags
{
    /**
     * Return the tags for email messages that can be tagged with multiple different 'tags'
     *
     * @return iterable|string[]
     */
    public function getTags() : iterable;
}
