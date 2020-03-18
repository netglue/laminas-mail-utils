<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

trait OpenTrackingBehaviour
{
    /** @var bool */
    private $trackOpens = true;

    public function shouldTrackOpens() : bool
    {
        return $this->trackOpens;
    }

    public function trackOpens(bool $flag) : void
    {
        $this->trackOpens = $flag;
    }
}
