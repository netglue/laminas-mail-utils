<?php
declare(strict_types=1);

namespace Netglue\MailTest\Validator;

use Laminas\Mail\Message;
use Netglue\Mail\Validator\HasSubject;
use PHPUnit\Framework\TestCase;

class HasSubjectTest extends TestCase
{
    /** @var HasSubject */
    private $validator;

    protected function setUp() : void
    {
        parent::setUp();
        $this->validator = new HasSubject();
    }

    public function testNonMessageIsNotValid() : void
    {
        $this->assertFalse($this->validator->isValid('foo'));
        $this->assertArrayHasKey(HasSubject::NOT_MESSAGE, $this->validator->getMessages());
    }

    public function testMissingSubjectIsInvalid() : void
    {
        $message = new Message();
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(HasSubject::NO_SUBJECT, $this->validator->getMessages());
    }

    public function testIsValidWhenSubjectIsPresent() : void
    {
        $message = new Message();
        $message->setSubject('foo');
        $this->assertTrue($this->validator->isValid($message));
    }

    public function testIsInvalidWhenSubjectIsLongerThanMax() : void
    {
        $this->validator->setMax(2);
        $message = new Message();
        $message->setSubject('foo');
        $this->assertFalse($this->validator->isValid($message));
        $this->assertArrayHasKey(HasSubject::SUBJECT_TOO_LONG, $this->validator->getMessages());
        $this->assertEquals(
            'The string length of the subject exceeds the maximum of 2',
            $this->validator->getMessages()[HasSubject::SUBJECT_TOO_LONG]
        );
    }

    public function testIsValidWhenSubjectIsNotLongerThanMax() : void
    {
        $this->validator->setMax(3);
        $message = new Message();
        $message->setSubject('foo');
        $this->assertTrue($this->validator->isValid($message));
    }
}
