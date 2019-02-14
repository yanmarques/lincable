<?php

namespace Tests\Lincable;

use Closure;
use Lincable\MediaManager;
use Tests\Lincable\TestCase;
use Illuminate\Container\Container;
use Lincable\Eloquent\Events\UploadSuccess;
use Lincable\Eloquent\Events\UploadFailure;
use Lincable\Providers\MediaManagerServiceProvider;

class MediaManagerServiceProviderTest extends TestCase
{
    private $provider;

    public function setUp()
    {
        parent::setUp();
        $this->provider = new MediaManagerServiceProvider(Container::getInstance());
    }

    /**
     * Should register the service provider and make the media manager available
     * on container.
     *
     * @return void
     */
    public function testRegisterWillResolveMediaManagerSingleton()
    {
        $this->setDisk('s3');
        $this->provider->register();
        
        $container = Container::getInstance();
        $this->assertInstanceOf(MediaManager::class, $container->make(MediaManager::class));
    }

    /**
     * Should register the upload subscriber to events.
     *
     * @return void
     */
    public function testBootSubscriberUploadSubscriberFromConfiguration()
    {
        $this->setDisk('s3');
        $this->provider->boot();

        $subscribers = Container::getInstance()['events']->getListeners(UploadSuccess::class);
        
        $this->assertInstanceOf(
            Closure::class,
            head($subscribers)
        );
    }

    /**
     * Should boot the lincable configuration.
     *
     * @return void
     */
    public function testBootWillRegisterTheConfigurationFile()
    {
        $this->provider->boot();
        $config = __DIR__.'/../../config/lincable.php';
        
        $container = Container::getInstance();
        $this->assertEquals(require $config, $container['config']['lincable']);
    }
}
