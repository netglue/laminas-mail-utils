<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

use function array_search;
use function in_array;

trait MultipleTagsBehaviour
{
    /** @var string[] */
    private $tags = [];

    public function addTag(string $tag) : void
    {
        if (empty($tag) || in_array($tag, $this->tags, true)) {
            return;
        }

        $this->tags[] = $tag;
    }

    /** @return string[] */
    public function getTags() : iterable
    {
        return $this->tags;
    }

    public function removeTag(string $tag) : void
    {
        $key = array_search($tag, $this->tags, true);
        if ($key === false) {
            return;
        }

        unset($this->tags[$key]);
    }
}
