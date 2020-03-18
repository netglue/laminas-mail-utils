<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\HasFromAddress;
use PHPUnit\Framework\TestCase;

class HasFromAddressTest extends TestCase
{
    /** @var HasFromAddress */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new HasFromAddress();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(HasFromAddress::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testMissingFromIsInvalid() : void
    {
        $message = new Message();
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(HasFromAddress::MISSING_FROM, $this->validator->getMessages());
    }

    public function testIsValidWhenFromIsPresent() : void
    {
        $message = new Message();
        $message->setFrom('me@example.com');
        $this->assertTrue($this->validator->isValid($message));
    }
}
