<?php
declare(strict_types=1);

namespace Netglue\Mail\Validator;

use Laminas\Mail\Message;
use function assert;
use function count;

final class TotalRecipientCount extends IsMessage
{
    public const TOO_MANY_RECIPIENTS = 'TooManyRecipients';

    /** @var string[] */
    protected $messageTemplates = [
        self::NOT_MESSAGE => 'Expected a Message object',
        self::TOO_MANY_RECIPIENTS => 'The maximum number of recipients is %max% but this message has %count%.',
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

        $this->count = count($value->getTo());
        $this->count += count($value->getCc());
        $this->count += count($value->getBcc());

        if ($this->count > $this->max) {
            $this->error(self::TOO_MANY_RECIPIENTS);

            return false;
        }

        return true;
    }
}
