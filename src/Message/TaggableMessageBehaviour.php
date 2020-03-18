<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

trait TaggableMessageBehaviour
{
    /** @var string|null */
    private $tag;

    public function setTag(?string $tag) : void
    {
        $tag = empty($tag) ? null : $tag;
        $this->tag = $tag;
    }

    public function getTag() :? string
    {
        return $this->tag;
    }
}
