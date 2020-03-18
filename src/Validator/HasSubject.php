<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use function assert;
use function strlen;

final class HasSubject extends IsMessage
{
    public const NO_SUBJECT = 'NO_SUBJECT';
    public const SUBJECT_TOO_LONG = 'SUBJECT_TOO_LONG';

    /** @var string[] */
    protected $messageTemplates = [
        self::NOT_MESSAGE => 'Expected a Message object',
        self::NO_SUBJECT => 'This message has no Subject',
        self::SUBJECT_TOO_LONG => 'The string length of the subject exceeds the maximum of %max%',
    ];

    /** @var int|null */
    protected $max;

    /** @var string[] */
    protected $messageVariables = ['max' => 'max'];

    public function setMax(int $max) : void
    {
        $this->max = $max;
    }

    /** @inheritDoc */
    public function isValid($value) : bool
    {
        if (! parent::isValid($value)) {
            return false;
        }

        assert($value instanceof Message);

        if (empty($value->getSubject())) {
            $this->error(self::NO_SUBJECT);

            return false;
        }

        if (! $this->max) {
            return true;
        }

        if (strlen($value->getSubject()) > $this->max) {
            $this->error(self::SUBJECT_TOO_LONG);

            return false;
        }

        return true;
    }
}
