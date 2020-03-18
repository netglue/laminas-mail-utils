<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use function assert;
use function count;

final class HasFromAddress extends IsMessage
{
    public const MISSING_FROM = 'MissingFrom';

    /** @var string[] */
    protected $messageTemplates = [
        self::NOT_MESSAGE => 'Expected a Message object',
        self::MISSING_FROM => 'There is no From: address',
    ];

    /** @inheritDoc */
    public function isValid($value) : bool
    {
        if (! parent::isValid($value)) {
            return false;
        }

        assert($value instanceof Message);

        $from = $value->getFrom();
        if (count($from) === 0) {
            $this->error(self::MISSING_FROM);

            return false;
        }

        return true;
    }
}
