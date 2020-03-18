<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

interface OpenTracking
{
    /**
     * Whether open tracking should be enabled or not for this email message
     */
    public function shouldTrackOpens() : bool;
}
