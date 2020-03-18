<?php
declare(strict_types=1);

namespace Netglue\MailTest\Message;

use Netglue\Mail\Exception\InvalidArgument;
use Netglue\Mail\Message\KeyValueMetadata;
use Netglue\Mail\Message\KeyValueMetadataBehaviour;
use PHPUnit\Framework\TestCase;
use stdClass;

class KeyValueMetadataBehaviourTest extends TestCase
{
    /** @var KeyValueMetadata */
    private $subject;

    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new class implements KeyValueMetadata {
            use KeyValueMetadataBehaviour;
        };
    }

    public function testThatMetadataKeysCannotBeEmpty() : void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage('Metadata keys cannot be empty');
        $this->subject->addMetaData('', 'Foo');
    }

    public function testNonScalarValuesAreExceptional() : void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage('Expected the metadata value to be scalar. Received object');
        $this->subject->addMetaData('keyname', new stdClass());
    }

    public function testThatMetaDataCanBeAdded() : void
    {
        $expect = ['foo' => 'bar'];
        $this->subject->addMetaData('foo', 'bar');
        $this->assertEquals($expect, $this->subject->getMetaData());
    }

    public function testThatSetMetadataCanBeUsedToSetMultipleValues() : void
    {
        $expect = ['a' => 'b', 'c' => 'd'];
        $this->subject->setMetaData($expect);
        $this->assertEquals($expect, $this->subject->getMetaData());
    }

    public function testThatSettingMetadataWithAnArrayOverwritePreviousValues() : void
    {
        $first = ['a' => 'b', 'c' => 'd'];
        $second = ['e' => 'f', 'g' => 'h'];
        $this->subject->setMetaData($first);
        $this->subject->setMetaData($second);
        $this->assertEquals($second, $this->subject->getMetaData());
        $this->assertArrayNotHasKey('a', $this->subject->getMetaData());
    }
}
