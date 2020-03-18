<?php
declare(strict_types=1);

namespace Netglue\MailTest\Message;

use Netglue\Mail\Message\OpenTracking;
use Netglue\Mail\Message\OpenTrackingBehaviour;
use PHPUnit\Framework\TestCase;

class OpenTrackingBehaviourTest extends TestCase
{
    /** @var OpenTracking */
    private $subject;

    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new class implements OpenTracking {
            use OpenTrackingBehaviour;
        };
    }

    public function testThatOpenTrackingHasDefaultBooleanValue() : void
    {
        $this->assertIsBool($this->subject->shouldTrackOpens());
    }

    public function testThatOpenTrackingCanBeToggledOnOrOff() : void
    {
        $this->subject->trackOpens(false);
        $this->assertFalse($this->subject->shouldTrackOpens());

        $this->subject->trackOpens(true);
        $this->assertTrue($this->subject->shouldTrackOpens());
    }
}
