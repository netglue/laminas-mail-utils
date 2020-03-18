<?php
declare(strict_types=1);

namespace Netglue\MailTest\Message;

use Netglue\Mail\Message\TaggableMessage;
use Netglue\Mail\Message\TaggableMessageBehaviour;
use PHPUnit\Framework\TestCase;

class TaggableMessageBehaviourTest extends TestCase
{
    /** @var TaggableMessage */
    private $subject;

    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new class implements TaggableMessage {
            use TaggableMessageBehaviour;
        };
    }

    public function testThatTagIsInitiallyNull() : void
    {
        $this->assertNull($this->subject->getTag());
    }

    public function testThatTagCanBeSet() : void
    {
        $this->subject->setTag('foo');
        $this->assertSame('foo', $this->subject->getTag());
    }

    public function testThatTagCanBeChanged() : void
    {
        $this->subject->setTag('foo');
        $this->subject->setTag('bar');
        $this->assertSame('bar', $this->subject->getTag());
    }

    public function testThatTagCanBeNullified() : void
    {
        $this->subject->setTag('foo');
        $this->subject->setTag(null);
        $this->assertNull($this->subject->getTag());
    }
}
