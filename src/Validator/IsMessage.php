<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use Laminas\Validator\AbstractValidator;

abstract class IsMessage extends AbstractValidator
{
    public const NOT_MESSAGE = 'notAMessageObject';

    /** @var string[] */
    protected $messageTemplates = [self::NOT_MESSAGE => 'Expected a Message object'];

    /** @inheritDoc */
    public function isValid($value) : bool
    {
        if (! $value instanceof Message) {
            $this->error(self::NOT_MESSAGE);

            return false;
        }

        return true;
    }
}
