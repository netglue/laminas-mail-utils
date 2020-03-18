<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use function assert;
use function count;

final class TotalFromCount extends IsMessage
{
    public const TOO_MANY_SENDERS = 'TooManyRecipients';

    /** @var string[] */
    protected $messageTemplates = [
        self::NOT_MESSAGE => 'Expected a Message object',
        self::TOO_MANY_SENDERS => 'The maximum number of From: headers allowed is %max% but this message has %count%.',
    ];

    /** @var int|null */
    protected $max;

    /** @var int|null */
    protected $count;

    /** @var string[] */
    protected $messageVariables = [
        'max'  => 'max',
        'count' => 'count',
    ];

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

        if (! $this->max) {
            return true;
        }

        $this->count = count($value->getFrom());

        if ($this->count > $this->max) {
            $this->error(self::TOO_MANY_SENDERS);

            return false;
        }

        return true;
    }
}
