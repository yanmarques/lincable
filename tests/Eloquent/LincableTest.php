<?php

namespace Tests\Lincable;

use Mockery;
use Illuminate\Http\File;
use Lincable\UrlCompiler;
use Lincable\MediaManager;
use Tests\Lincable\TestCase;
use Lincable\Eloquent\Lincable;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\PostgresConnection;
use Lincable\Eloquent\Events\UploadSuccess;
use Lincable\Eloquent\Events\UploadFailure;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Lincable\Exceptions\ConflictFileUploadHttpException;
use Illuminate\Database\Query\Processors\PostgresProcessor;

class LincableTest extends TestCase
{
    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithValidFile()
    {
        Event::fake();

        $this->setDisk('foo');
        $this->setConfig('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);

        $this->bindMediaManager();
    
        // Create the random file with random text.
        $file = new File(tap('/tmp/'.$this->getRandom('txt'), function ($file) {
            touch($file);
            file_put_contents($file, str_random());
        }));
        
        $media = $this->createModel(['id' => 123]);
        $media->link($file);

        Event::assertDispatched(UploadSuccess::class);
        $this->assertTrue(file_exists($media->preview));
    }

    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithInvalidFile()
    {
        Event::fake();

        $this->setDisk('foo');
        $this->setConfig('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);

        $this->bindMediaManager();
    
        // Create the random file with random text.
        $file = new File('/tmp/'.$this->getRandom('txt'), false);
        
        $media = $this->createModel(['id' => 123]);

        $this->expectException(ConflictFileUploadHttpException::class);
        $media->link($file);

        Event::assertDispatched(UploadFailure::class);
        $this->assertFalse(file_exists($media->preview));
    }

    /**
     * Should execute the callback and get the contents of
     * stored file.
     *
     * @return void
     */
    public function testWithMediaGetTheLinkedFile()
    {
        Event::fake();

        $this->setDisk('foo');
        $this->setConfig('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);

        $this->bindMediaManager();

        $expected = str_random();
    
        // Create the random file with random text.
        $file = new File(tap('/tmp/'.$this->getRandom('txt'), function ($file) use ($expected) {
            touch($file);
            file_put_contents($file, $expected);
        }));
        
        $media = $this->createModel(['id' => 123]);
        $media->link($file);

        $media->withMedia(function ($file) use ($expected) {
            $this->assertEquals($expected, file_get_contents($file->path()));
        });
    }

    /**
     * Should return the relative url.
     *
     * @return void
     */
    public function testGetUrlReturnsRegisteredUrlOnUrlConf()
    {
        $this->setDisk('foo');
        $this->setConfig('lincable.urls', [
            Media::class => 'foo/:id'
        ]);

        $this->bindMediaManager();

        // Create the random file with random text.
        $file = new File(tap('/tmp/'.$this->getRandom('txt'), function ($file) {
            touch($file);
            file_put_contents($file, str_random());
        }));
        
        $media = $this->createModel(['id' => 123]);
        $media->link($file);
        
        $this->assertEquals(
            str_after($media->preview, '/tmp/'),
            $media->getUrl()
        );
    }

    /**
     * Bind a new media manager to container.
     *
     * @return void
     */
    protected function bindMediaManager()
    {
        Container::getInstance()->singleton(MediaManager::class, function ($app) {
            return new MediaManager($app, new UrlCompiler);
        });
    }

    /**
     * Return a mocked model object.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createModel(array $attributes)
    {
        $media = new Media($attributes);
        $mockObject = Mockery::mock(PostgresConnection::class);
        $mockObject->shouldReceive('getQueryGrammar')->andReturn(new PostgresGrammar);
        $mockObject->shouldReceive('getPostProcessor')->andReturn(new PostgresProcessor);
        $mockObject->shouldReceive('selectFromWriteConnection')->andReturn([$attributes]);
        $connectionResolver = new ConnectionResolver(['test' => $mockObject]);
        $media->setConnectionResolver($connectionResolver);
        $media->setConnection('test');
        return $media;
    }
}

class Media extends Model
{
    use Lincable;

    protected $fillable = [
        'id'
    ];
}
