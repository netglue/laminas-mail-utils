<?php
declare(strict_types=1);

namespace Netglue\MailTest\Message;

use Netglue\Mail\Message\MultipleTags;
use Netglue\Mail\Message\MultipleTagsBehaviour;
use PHPUnit\Framework\TestCase;

class MultipleTagsBehaviourTest extends TestCase
{
    /** @var MultipleTags */
    private $subject;

    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new class implements MultipleTags {
            use MultipleTagsBehaviour;
        };
    }

    public function testThatTagsAreInitiallyEmpty() : void
    {
        $this->assertEmpty($this->subject->getTags());
        $this->assertCount(0, $this->subject->getTags());
    }

    public function testThatTagsCanBeAdded() : void
    {
        $this->subject->addTag('foo');
        $this->assertContains('foo', $this->subject->getTags());
    }

    public function testThatEmptyStringsAreIgnored() : void
    {
        $this->subject->addTag('');
        $this->assertCount(0, $this->subject->getTags());
    }

    public function testThatIdenticalTagsAreIgnored() : void
    {
        $this->subject->addTag('foo');
        $this->assertCount(1, $this->subject->getTags());
        $this->subject->addTag('foo');
        $this->assertCount(1, $this->subject->getTags());
    }

    public function testThatTagsCanBeRemoved() : void
    {
        $this->subject->addTag('foo');
        $this->subject->addTag('bar');
        $this->assertCount(2, $this->subject->getTags());
        $this->subject->removeTag('foo');
        $this->assertContains('bar', $this->subject->getTags());
        $this->assertNotContains('foo', $this->subject->getTags());
        $this->assertCount(1, $this->subject->getTags());
    }

    public function testThatRemovingNonExistentTagsIsANoOp() : void
    {
        $this->subject->removeTag('not-there');
        $this->addToAssertionCount(1);
    }
}
