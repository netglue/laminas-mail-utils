<?php
declare(strict_types=1);

namespace Netglue\MailTest;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Validator\ConfigProvider as LaminasValidatorConfig;
use Laminas\Validator\ValidatorPluginManager;
use Netglue\Mail\ConfigProvider;
use Netglue\Mail\Validator\HasFromAddress;
use Netglue\Mail\Validator\HasSubject;
use Netglue\Mail\Validator\HasToRecipient;
use Netglue\Mail\Validator\TotalFromCount;
use Netglue\Mail\Validator\TotalRecipientCount;
use Netglue\Mail\Validator\TotalReplyToCount;
use PHPUnit\Framework\TestCase;

class LaminasValidatorManagerIntegrationTest extends TestCase
{
    /** @var ValidatorPluginManager */
    private $pluginManager;

    protected function setUp() : void
    {
        parent::setUp();

        $aggregator = new ConfigAggregator([
            LaminasValidatorConfig::class,
            ConfigProvider::class,
        ]);

        $config = $aggregator->getMergedConfig();
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;
        $container = new ServiceManager($dependencies);
        $this->pluginManager = $container->get(ValidatorPluginManager::class);
    }

    /** @return string[][] */
    public function validatorClassList() : iterable
    {
        $list = [
            HasFromAddress::class,
            HasSubject::class,
            HasToRecipient::class,
            TotalFromCount::class,
            TotalRecipientCount::class,
            TotalReplyToCount::class,
        ];

        foreach ($list as $class) {
            yield $class => [$class];
        }
    }

    /** @dataProvider validatorClassList */
    public function testValidatorTypeCanBeRetrievedFromPluginManager(string $class) : void
    {
        $this->assertTrue($this->pluginManager->has($class));
        $instance = $this->pluginManager->get($class);
        $this->assertInstanceOf($class, $instance);
    }
}
