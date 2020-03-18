<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

interface TaggableMessage
{
    /**
     * Return the tag assigned to this message
     */
    public function getTag() :? string;
}
