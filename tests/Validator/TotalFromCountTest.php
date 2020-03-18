<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\TotalFromCount;
use PHPUnit\Framework\TestCase;

class TotalFromCountTest extends TestCase
{
    /** @var TotalFromCount */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new TotalFromCount();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(TotalFromCount::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testIsValidWhenMaxSendersIsNotSet() : void
    {
        $message = new Message();
        $message->addFrom('a@b.com');
        $message->addFrom('b@b.com');
        $message->addFrom('c@b.com');
        $this->assertTrue($this->validator->isValid($message));
    }

    public function testInvalidWhenCountExceedsMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addFrom('a@b.com');
        $message->addFrom('b@b.com');
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(TotalFromCount::TOO_MANY_SENDERS, $this->validator->getMessages());
        $this->assertEquals(
            'The maximum number of From: headers allowed is 1 but this message has 2.',
            $this->validator->getMessages()[TotalFromCount::TOO_MANY_SENDERS]
        );
    }

    public function testIsValidWhenCountDoesNotExceedMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addFrom('a@b.com');
        $this->assertTrue($this->validator->isValid($message));
    }
}
