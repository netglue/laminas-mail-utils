<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\TotalRecipientCount;
use PHPUnit\Framework\TestCase;

class TotalRecipientCountTest extends TestCase
{
    /** @var TotalRecipientCount */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new TotalRecipientCount();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(TotalRecipientCount::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testIsValidWhenMaxRecipientsIsNotSet() : void
    {
        $message = new Message();
        $this->assertTrue($this->validator->isValid($message));
    }

    public function testInvalidWhenCountExceedsMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addTo('a@b.com');
        $message->addTo('b@b.com');
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(TotalRecipientCount::TOO_MANY_RECIPIENTS, $this->validator->getMessages());
        $this->assertEquals(
            'The maximum number of recipients is 1 but this message has 2.',
            $this->validator->getMessages()[TotalRecipientCount::TOO_MANY_RECIPIENTS]
        );
    }

    public function testIsValidWhenCountDoesNotExceedMax() : void
    {
        $this->validator->setMax(1);
        $message = new Message();
        $message->addTo('a@b.com');
        $this->assertTrue($this->validator->isValid($message));
    }

    public function testToCcAndBccAreConsideredAsRecipients() : void
    {
        $this->validator->setMax(2);
        $message = new Message();
        $message->addTo('a@b.com');
        $message->addCc('a@b.com');
        $message->addBcc('a@b.com');
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(TotalRecipientCount::TOO_MANY_RECIPIENTS, $this->validator->getMessages());
    }
}
