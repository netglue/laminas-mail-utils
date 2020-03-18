<?php
declare(strict_types=1);

namespace Netglue\Mail;

use Laminas\ServiceManager\Factory\InvokableFactory;

class ConfigProvider
{
    /** @return mixed[] */
    public function __invoke() : array
    {
        return [
            'validators' => $this->validators(),
        ];
    }

    /** @return mixed[] */
    private function validators() : array
    {
        return [
            'factories' => [
                Validator\HasFromAddress::class => InvokableFactory::class,
                Validator\HasSubject::class => InvokableFactory::class,
                Validator\HasToRecipient::class => InvokableFactory::class,
                Validator\TotalFromCount::class => InvokableFactory::class,
                Validator\TotalRecipientCount::class => InvokableFactory::class,
                Validator\TotalReplyToCount::class => InvokableFactory::class,
            ],
        ];
    }
}
