<?php

namespace Tests\Eloquent;

use Event;
use Storage;
use Illuminate\Http\File;
use Lincable\MediaManager;
use Tests\Lincable\TestCase;
use Lincable\Eloquent\Lincable;
use Tests\Lincable\Models\Media;
use Illuminate\Http\UploadedFile;
use Lincable\Eloquent\Events\UploadSuccess;
use Tests\Lincable\Models\MediaWithMutator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lincable\Exceptions\ConflictFileUploadHttpException;

class LincableTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithValidFile()
    {
        Event::fake();

        $this->setUrls([
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
        $this->assertTrue(Storage::exists($media->getRawUrl()));
    }

    /**
     * Should link the file with model and create store the file.
     *
     * @return void
     */
    public function testLinkWithInvalidFile()
    {
        $this->setUrls([
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
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);

        $expected = str_random();
    
        // Create the random file with random text.
        $file = $this->createFile($expected);
        
        $media = new Media(['id' => 123]);
        $media->link($file);

        $media->withMedia(function ($file) use ($expected) {
            $this->assertEquals($expected, \file_get_contents($file->path()));
        });
    }

    /**
     * Should return the relative url.
     *
     * @return void
     */
    public function testGetUrlReturnsRegisteredUrlOnUrlConf()
    {
        $this->setUrls([
            Media::class => 'foo/:id'
        ]);

        // Create the random file with random text.
        $file = $this->createFile(str_random());
        
        $media = new Media(['id' => 123]);
        $media->link($file);
        
        $this->assertContains($media->getRawUrl(), $media->preview);
    }

    /**
     * Should re-link the model to another file, keeping
     * the same url.
     *
     * @return void
     */
    public function testUseModelUrlWhenAlreadySet()
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

        // Get the old url.
        $oldUrl = $media->preview;
        
        // Create a new file to link with.
        $newlyFile = new File(tap('/tmp/'.$this->getRandom('txt'), function ($file) {
            touch($file);
            file_put_contents($file, str_random());
        }));

        $media->link($newlyFile);
        
        $this->assertEquals($oldUrl, $media->preview);
    }

    /**
     * Should re-link the model to another file, keeping
     * the same url.
     *
     * @return void
     */
    public function testUseModelUrlWhenAlreadySet()
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

        // Get the old url.
        $oldUrl = $media->preview;
        
        // Create a new file to link with.
        $newlyFile = new File(tap('/tmp/'.$this->getRandom('txt'), function ($file) {
            touch($file);
            file_put_contents($file, str_random());
        }));

        $media->link($newlyFile);
        
        $this->assertEquals($oldUrl, $media->preview);
    }

    /**
     * Bind a new media manager to container.
     *
     * @return void
     */
    public function testUseModelUrlWhenSet()
    {
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        // Create the random file with random text.
        $file = $this->createFile(str_random());
        
        $media = new Media(['id' => 123]);
        $media->link($file);

        // Get the old filename.
        $oldFilename = $media->getFileName();
        
        // Create a new file to link with.
        $newlyFile = $this->createFile(str_random());

        $media->link($newlyFile);
        
        $this->assertEquals($oldFilename, $media->getFileName());
    }

    /**
     * Should replicate model data cloning the file.
     * 
     * @return void
     */
    public function testReplicateModelAndCloneMedia()
    {
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $expected = str_random();
        
        $media = new Media(['id' => 123]);
        $media->link($this->createFile($expected));
        
        $clone = $media->replicate();
        $clone->save();

        $this->assertNotEquals($media->getRawUrl(), $clone->getRawUrl());

        $this->assertStringStartsNotWith('/', $clone->getRawUrl());

        $clone->withMedia(function ($file) use ($expected) {
            $this->assertEquals($expected, file_get_contents($file->path()));
        });
    }

    /**
     * Should clone the model keeping the source model filename.
     * 
     * @return void
     */
    public function testReplicateWithPreserveNameEnabled()
    {
        $this->setUrls([
            Media::class => 'foo/:id'
        ]);
    
        $expected = str_random();
        
        $media = new Media(['id' => 123]);
        $media->link($this->createFile($expected));

        $clone = $media->replicate();
        $clone->preserveName = true;
        $clone->save();
        
        $this->assertEquals($media->getFileName(), $clone->getFileName());
    }

    /**
     * Should link the created model without firing model save events.
     * 
     * @return void
     */
    public function testReplicateModelAndSavedEventIsNotDispatched()
    {
        Event::fake();

        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new Media(['id' => 123]);
        $media->link($this->createFile(""));

        $clone = $media->replicate();
        $clone->save();

        Event::assertNotDispatched('eloquent.updated: '.get_class($clone));
    }

    /**
     * Should delete the media when model is deleted.
     *
     * @return void
     */
    public function testDeleteModelRemovesMediaOnStorageByDefault()
    {
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new Media(['id' => 123]);
        $media->link($this->createFile(""));
        $media->delete();

        $this->assertFalse($media::getMediaManager()->has($media));
    }

    /**
     * Should keep the media when model is deleted.
     *
     * @return void
     */
    public function testKeepTheMediaWhenDeletedWhenLocallyConfigured()
    {
        $this->app['config']->set('lincable.keep_media_on_delete', false);

        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new Media(['id' => 123]);
        $media->keepMediaOnDelete = true;
        $media->link($this->createFile(""));
        $media->delete();

        $this->assertTrue($media::getMediaManager()->has($media));
    }

    /**
     * Should delete the media when model is deleted.
     *
     * @return void
     */
    public function testUseGlobalConfigurationWhenLocallyIsNotPresent()
    {
        $this->app['config']->set('lincable.keep_media_on_delete', true);

        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new Media(['id' => 123]);
        $media->link($this->createFile(""));
        $media->delete();

        $this->assertTrue($media::getMediaManager()->has($media));
    }

    /**
     * Should overwrite method to get the full url from model.
     *
     * @return void
     */
    public function testUseCustomModelPrefixWhenHasGetAcessor()
    {
        $this->setUrls([
            MediaWithMutator::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new MediaWithMutator(['id' => 123]);
        $media->prefix = 'test/';
        $media->link($this->createFile(""));
        
        $this->assertTrue(starts_with($media->preview, 'test/'));
    }

    /**
     * Should use local configured model url field.
     *
     * @return void
     */
    public function testUseUrlFieldConfiguredOnModel()
    {
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);

        $expected = 'foo';
    
        $media = new Media(['id' => 123]);
        $media->urlField = $expected;

        $this->assertEquals($expected, $media->getUrlField());    
    }

    /**
     * Should transform the model to a readable html string.
     *
     * @return void
     */
    public function testHtmalbleContractImplementation()
    {
        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
    
        $media = new Media(['id' => 123]);
        $media->link($this->createFile(""));

        $this->assertStringContainsString($media->getUrl(), e($media));
    }

    /**
     * Should create the model based on request firing properly events.
     *
     * @return void
     */
    public function testCreateWithFileRequestScopeOnlyFireCreateEvent()
    {
        Event::fake();

        $this->setUrls([
            Media::class => 'foo/:year/:month/:id'
        ]);
        
        $request = $this->createFileRequest('txt')->merge(['id' => 123]);
    
        $media = Media::createWithFileRequest($request);

        $this->assertTrue(app(MediaManager::class)->has($media));
    
        Event::assertDispatched('eloquent.created: '.Media::class);
        Event::assertDispatched('eloquent.creating: '.Media::class);
        Event::assertDispatched(UploadSuccess::class);
        Event::assertNotDispatched('eloquent.updated: '.Media::class);
    }
}
