<?php
declare(strict_types=1);

namespace Netglue\Mail\Message;

use Netglue\Mail\Exception\InvalidArgument;
use function gettype;
use function is_scalar;
use function sprintf;

trait KeyValueMetadataBehaviour
{
    /** @var mixed[] */
    private $metaData = [];

    /** @param mixed $value */
    public function addMetaData(string $key, $value) : void
    {
        if (empty($key)) {
            throw new InvalidArgument(
                'Metadata keys cannot be empty'
            );
        }

        if (! is_scalar($value) && $value !== null) {
            throw new InvalidArgument(sprintf(
                'Expected the metadata value to be scalar. Received %s.',
                gettype($value)
            ));
        }

        $this->metaData[$key] = $value;
    }

    /** @param mixed[] $metadata */
    public function setMetaData(iterable $metadata) : void
    {
        $this->metaData = [];
        foreach ($metadata as $key => $value) {
            $this->addMetaData($key, $value);
        }
    }

    /** @return mixed[] $value */
    public function getMetaData() : iterable
    {
        return $this->metaData;
    }
}
