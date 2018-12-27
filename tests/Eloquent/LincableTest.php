<?php

namespace Tests\Eloquent;

use Event;
use Storage;
use Illuminate\Http\File;
use Tests\Lincable\TestCase;
use Lincable\Eloquent\Lincable;
use Tests\Lincable\Models\Media;
use Illuminate\Http\UploadedFile;
use Lincable\Eloquent\Events\UploadSuccess;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lincable\Exceptions\ConflictFileUploadHttpException;

class LincableTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithValidFile()
    {
        Event::fake();

        $this->app['config']->set('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        // Create the random file with random text.
        $file = UploadedFile::fake()->create(
            $this->getRandom('txt'),
            10
        );
        
        $media = new Media(['id' => 123]);
        $media->link($file);
        
        Event::assertDispatched(UploadSuccess::class);
        $this->assertTrue(Storage::exists($media->getUrl()));
    }

    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithInvalidFile()
    {
        $this->app['config']->set('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        // Create the random file with random text.
        $file = new File(str_random(), false);
        
        $media = new Media(['id' => 123]);
        
        $this->expectException(ConflictFileUploadHttpException::class);
        $media->link($file);
    }

    /**
     * Should execute the callback and get the contents of
     * stored file.
     *
     * @return void
     */
    public function testWithMediaGetTheLinkedFile()
    {
        $this->app['config']->set('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);

        $expected = str_random();
    
        // Create the random file with random text.
        $file = $this->createFile($expected);
        
        $media = new Media(['id' => 123]);
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
        $this->app['config']->set('lincable.urls', [
            Media::class => 'foo/:id'
        ]);

        // Create the random file with random text.
        $file = $this->createFile(str_random());
        
        $media = new Media(['id' => 123]);
        $media->link($file);
        
        $this->assertContains($media->getUrl(), $media->preview);
    }

    /**
     * Should re-link the model to another file, keeping
     * the same url.
     *
     * @return void
     */
    public function testUseModelUrlWhenAlreadySet()
    {
        $this->app['config']->set('lincable.urls', [
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        // Create the random file with random text.
        $file = $this->createFile(str_random());
        
        $media = new Media(['id' => 123]);
        $media->link($file);

        // Get the old url.
        $oldUrl = $media->preview;
        
        // Create a new file to link with.
        $newlyFile = $this->createFile(str_random());

        $media->link($newlyFile);
        
        $this->assertEquals($oldUrl, $media->preview);
    }
}
