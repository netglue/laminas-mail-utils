# Laminas Mail Utilities

![PHPUnit Test Suite](https://github.com/netglue/laminas-mail-utils/workflows/PHPUnit%20Test%20Suite/badge.svg)
[![codecov](https://codecov.io/gh/netglue/laminas-mail-utils/branch/master/graph/badge.svg)](https://codecov.io/gh/netglue/laminas-mail-utils)

> [!CAUTION]
> This library is abandoned because `laminas-mail` has been too…

### Introduction

This is a small package to scratch two primary itches

 - Validate `Laminas\Mail\Message` instances according to configurable constraints such as a maximum number of recipients, or a non-empty subject line for example.
 - Provide simple behaviours and interfaces for email messages that should be sent via popular transactional or marketing email service providers; for example, key/value metadata or tagging/categorisation.

### Installation

```bash
composer require netglue/laminas-mail-utils
```

### Shipped Validators

Validators can be used as part of your input filter setup, or by creating vendor specific validator chains. Here's an example of a concrete validator chain that would be helpful validating messages to be sent via the Postmark API, which imposes certain restrictions on recipients etc:

```php
<?php
declare(strict_types=1);

namespace App;

use Netglue\Mail\Validator\HasFromAddress;
use Netglue\Mail\Validator\HasSubject;
use Netglue\Mail\Validator\HasToRecipient;
use Netglue\Mail\Validator\TotalFromCount;
use Netglue\Mail\Validator\TotalRecipientCount;
use Netglue\Mail\Validator\TotalReplyToCount;
use Laminas\Validator\ValidatorChain;
use Laminas\Validator\ValidatorPluginManager;

final class PostmarkMessageValidator extends ValidatorChain
{
  	private const MAX_RECIPIENTS = 50;
 		
    public function __construct(?ValidatorPluginManager $pluginManager = null)
    {
        parent::__construct();
        if ($pluginManager) {
            $this->setPluginManager($pluginManager);
        }

        $this->configureDefaults();
    }

    private function configureDefaults() : void
    {
        $this->attachByName(HasFromAddress::class);
        $this->attachByName(TotalFromCount::class, ['max' => 1]);
        $this->attachByName(HasSubject::class);
        $this->attachByName(HasToRecipient::class);
        $this->attachByName(TotalRecipientCount::class, ['max' => self::MAX_RECIPIENTS]);
        $this->attachByName(TotalReplyToCount::class, ['max' => 1]);
    }
}

```

### Shipped Traits & Interfaces

You'll find a collection of interfaces that are pretty minimal but hopefully encapsulate what a lot of transactional email service providers offer when it comes to additional features WRT sending mail - for example, assigning tags or categories to individual messages or turning click tracking or open tracking on and off.

Typically, you make a concrete descendant of `Laminas\Mail\Message` and implement whichever interfaces suit the provider best so that you have type safety when working with the capabilities of any given message instance. Again, using Postmark as an example, perhaps something like this:

```php
<?php
declare(strict_types=1);

namespace App\Postmark\Message;

use Netglue\Mail\Message\KeyValueMetadata;
use Netglue\Mail\Message\KeyValueMetadataBehaviour;
use Netglue\Mail\Message\OpenTracking;
use Netglue\Mail\Message\OpenTrackingBehaviour;
use Netglue\Mail\Message\TaggableMessage;
use Netglue\Mail\Message\TaggableMessageBehaviour;
use Laminas\Mail\Message;

class MyPostmarkMessage extends Message implements TaggableMessage, KeyValueMetadata, OpenTracking
{
    use OpenTrackingBehaviour;
    use TaggableMessageBehaviour;
    use KeyValueMetadataBehaviour {
        addMetaData as parentAddMetaData;
    }
}
```

```php
// $message is given to us from some factory or service somewhere
if ($message instanceof TaggableMessage) {
  $someVendorApi->setMessageTag($message->getTag());
}
```

### The plan…

… is to implement vendor specific packages that lever these utilities, with, as you may have guessed, Postmark at the top of the list currently…

The package is currently immature and subject to probable BC breaks and contributions are welcomed if this scratches any itches for you 👍
