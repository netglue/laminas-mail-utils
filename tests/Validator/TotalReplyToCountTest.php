<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\TotalReplyToCount;
use PHPUnit\Framework\TestCase;

class TotalReplyToCountTest extends TestCase
{
    /** @var TotalReplyToCount */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new TotalReplyToCount();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(TotalReplyToCount::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testIsValidWhenMaxIsNotSet() : void
    {
        $message = new Message();
        $message->addReplyTo('a@b.com');
        $message->addReplyTo('b@b.com');
        $message->addReplyTo('c@b.com');
        $this->assertTrue($this->validator->isValid($message));
    }

    public function testInvalidWhenCountExceedsMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addReplyTo('a@b.com');
        $message->addReplyTo('b@b.com');
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(TotalReplyToCount::TOO_MANY_HEADERS, $this->validator->getMessages());
        $this->assertEquals(
            'The maximum number of ReplyTo headers allowed is 1 but this message has 2.',
            $this->validator->getMessages()[TotalReplyToCount::TOO_MANY_HEADERS]
        );
    }

    public function testIsValidWhenCountDoesNotExceedMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addReplyTo('a@b.com');
        $this->assertTrue($this->validator->isValid($message));
    }
}
