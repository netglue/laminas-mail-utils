<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use function assert;
use function count;

final class HasToRecipient extends IsMessage
{
    public const MISSING_TO_RECIPIENT = 'MissingToRecipient';

    /** @var string[] */
    protected $messageTemplates = [
        self::NOT_MESSAGE => 'Expected a Message object',
        self::MISSING_TO_RECIPIENT => 'There is no To: recipient',
    ];

    /** @inheritDoc */
    public function isValid($value) : bool
    {
        if (! parent::isValid($value)) {
            return false;
        }

        assert($value instanceof Message);

        $to = $value->getTo();
        if (count($to) === 0) {
            $this->error(self::MISSING_TO_RECIPIENT);

            return false;
        }

        return true;
    }
}
