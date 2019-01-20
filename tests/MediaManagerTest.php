<?php

namespace Tests\Lincable;

use Event;
use Illuminate\Http\File;
use Lincable\MediaManager;
use Tests\Lincable\Models\Foo;
use Tests\Lincable\Models\Media;
use Lincable\Eloquent\Events\UploadSuccess;
use Lincable\Eloquent\Events\UploadFailure;
use League\Flysystem\FileNotFoundException;
use Lincable\Exceptions\ConflictFileUploadHttpException;

class MediaManagerTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('lincable.models.namespace', 'Tests\Lincable\Models'); 
    }

    /**
     * Should throw an exception when model does not use 
     * lincable trait.
     * 
     * @return void
     */
    public function testModelSupportToLincable()
    {
        $this->expectException(\Exception::class);

        app(MediaManager::class)->supportLincable(new Foo);
    }

    /**
     * Should determine support for an array of models.
     * 
     * @return void
     */
    public function testModelSupportToLincableWithArray()
    {
        $this->expectException(\Exception::class);

        app(MediaManager::class)->supportLincable([
            new Media,
            new Foo
        ]);
    }

    /**
     * Should return the full url for the model.
     * 
     * @return void
     */
    public function testUrlWillReturnTheFullUrlForModel()
    {
        $this->setUrls([
            'media' => 'foo/:id'
        ]);

        $manager = app(MediaManager::class);

        $model = new Media([
            'id' => 123
        ]);

        $model->preview = $manager->newLink($model);

        $url = $manager->url($model);
        
        $this->assertTrue(ends_with($url, '/foo/123'));
    }

    /**
     * Should return null when model is not linked.
     * 
     * @return void
     */
    public function testUrlWillReturnNullWhenModelDoesentHaveMediaLinked()
    {
        $model = new Media([
            'id' => 123
        ]);

        $result = app(MediaManager::class)->url($model);
        
        $this->assertNull($result);
    }

    /**
     * Should has the file of linked model on storage.
     *
     * @return void
     */
    public function testThatHasWillFindTheModelFile()
    {
        $this->setUrls([
            'media' => 'foo/:id'
        ]);

        $manager = app(MediaManager::class);

        $model = new Media([
            'id' => 123
        ]);

        $model->link($this->createFile(''));

        $this->assertTrue($manager->has($model));
    }

    /**
     * Should not find the model Media when model has no link.
     * 
     * @return void
     */
    public function testWillNotHaveANonLinkedModel()
    {
        $model = new Media([
            'id' => 123
        ]);

        $this->assertFalse(app(MediaManager::class)->has($model));
    }

    /**
     * Should upload the file to storage dispatching upload success event
     * and filling the model url attribute.
     * 
     * @return void
     */
    public function testWillUploadWithValidFileDispatchingEvent()
    {
        Event::fake();

        $this->setUrls([
            'media' => 'foo/:id'
        ]);
        
        $model = new Media([
            'id' => 123
        ]);
        
        app(MediaManager::class)->upload($this->createFile(''), $model);
            
        Event::assertDispatched(UploadSuccess::class);
        $this->assertTrue($model->isDirty($model->getUrlField()));
    }

    /**
     * Should upload a new Media with the same url when model
     * already has a Media linked.
     * 
     * @return void
     */
    public function testWillUploadNewMediaToLinkedModel()
    {   
        $this->setUrls([
            'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 666
        ]);

        // Link the model once.
        $model->link($this->createFile(''));

        $expected = str_random();
    
        app(MediaManager::class)->upload($this->createFile($expected), $model);
    
        $this->assertTrue($model->isClean($model->getUrlField()));

        $model->withMedia(function ($file) use ($expected) {
            $this->assertEquals($expected, file_get_contents($file->path()));
        });
    }    

    /**
     * Should dispatch event that of failure on upload and 
     * throw the given http exception.
     * 
     * @return void
     */
    public function testWillDispatchEventOnUploadWithInvalidFile()
    {
        Event::fake();

        $this->setUrls([
            'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);
        
        try {
            app(MediaManager::class)->upload(
                new File($this->getRandom('txt'), false), 
                $model
            );
        } catch (\Exception $ex) {
            $this->assertInstanceOf(ConflictFileUploadHttpException::class, $ex);
        }
        
        Event::assertDispatched(UploadFailure::class);
    }

    /**
     * Should copy media from model to the clone model with the
     * same contents.
     * 
     * @return void
     */
    public function testWillCopyTheMediaFromModel()
    {
        $this->setUrls([
           'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);

        $expected = str_random();

        // Link the model once.
        $model->link($this->createFile($expected));
        
        $clone = new Media([
            'id' => 124
        ]);

        app(MediaManager::class)->copy($model, $clone)
            ->withMedia(function ($file) use ($expected) {
                $this->assertEquals($expected, file_get_contents($file->path()));
            });
    }

    /**
     * Should copy media from model to the clone model preserving the 
     * original filename.
     * 
     * @return void
     */
    public function testWillCopyTheMediaFromModelPreservingName()
    {
        $this->setUrls([
           'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);

        // Link the model once.
        $model->link($this->createFile(''));
        
        $clone = new Media([
            'id' => 124
        ]);

        app(MediaManager::class)->copy($model, $clone, true);

        $this->assertEquals($model->getFilename(), $clone->getFileName());
    }

    /**
     * Should return the same file stored previously.
     * 
     * @return void
     */
    public function testWillReturnTheMediaInStorage()
    {
        $this->setUrls([
           'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);

        $expected = str_random();

        // Link the model once.
        $model->link($this->createFile($expected));
        
        $filePath = app(MediaManager::class)->get($model)->path();

        // Register the temporary file for further remove.
        $this->temps[] = $filePath;

        $this->assertEquals($expected, file_get_contents($filePath));
    }

    /**
     * Should throw an exception when the file is not present on storage.
     * 
     * @return void
     */
    public function testWillNotFindFileWithInvalidPath()
    {
        $model = new Media([
            'id' => 123
        ]);

        // Link the model once.
        $model->fillUrl(str_random());
        
        $this->expectException(FileNotFoundException::class);

        app(MediaManager::class)->get($model);
    }

    /**
     * Should throw exception when model url is null.
     * 
     * @return void
     */
    public function testWillThrowExceptionWhenModelIsNotLinked()
    {
        $model = new Media([
            'id' => 123
        ]);
        
        $this->expectException(FileNotFoundException::class);

        app(MediaManager::class)->get($model);
    }

    /**
     * Should create an url for the model without backslash 
     * at the end. 
     * 
     * @return void
     */
    public function testCreateLinkWithValidModel()
    {
        $this->setUrls([
           'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);

        // Link the model once.
        $url = app(MediaManager::class)->newLink($model);

        $this->assertTrue(ends_with($url, '/foo/123'));
    }

    /**
     * Should create a full link for model with a filename.
     *
     * @return void
     */
    public function testCreateLinkWithAFileName()
    {
        $this->setUrls([
            'media' => 'foo/:id'
        ]);

        $model = new Media([
            'id' => 123
        ]);

        // Link the model once.
        $url = app(MediaManager::class)->newLink($model, 'foo.txt');

        $this->assertTrue(ends_with($url, '/foo/123/foo.txt'));
    }
}
