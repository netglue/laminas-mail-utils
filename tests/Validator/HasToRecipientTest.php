<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\HasToRecipient;
use PHPUnit\Framework\TestCase;

class HasToRecipientTest extends TestCase
{
    /** @var HasToRecipient */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new HasToRecipient();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(HasToRecipient::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testMissingToRecipientIsInvalid() : void
    {
        $message = new Message();
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(HasToRecipient::MISSING_TO_RECIPIENT, $this->validator->getMessages());
    }

    public function testIsValidWhenToRecipientIsPresent() : void
    {
        $message = new Message();
        $message->addTo('me@example.com');
        $this->assertTrue($this->validator->isValid($message));
    }
}
