<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use App\Entity\Post;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function testImageIdIsNullByDefault(): void
    {
        $image = new Image();

        $this->assertNull($image->getId());
    }

    public function testImageTitleGetterSetter(): void
    {
        $image = new Image();
        $image->setTitle('Photo façade');

        $this->assertSame('Photo façade', $image->getTitle());
    }

    public function testImagePostGetterSetter(): void
    {
        $image = new Image();
        $post = new Post();

        $image->setPost($post);

        $this->assertSame($post, $image->getPost());
    }

    public function testImagePostCanBeNull(): void
    {
        $image = new Image();
        $image->setPost(null);

        $this->assertNull($image->getPost());
    }

    public function testImageIsInstanceOfMedia(): void
    {
        $image = new Image();

        $this->assertInstanceOf(\App\Entity\Media::class, $image);
    }

    public function testVideoIdIsNullByDefault(): void
    {
        $video = new Video();

        $this->assertNull($video->getId());
    }

    public function testVideoTitleGetterSetter(): void
    {
        $video = new Video();
        $video->setTitle('Tutoriel vidéo');

        $this->assertSame('Tutoriel vidéo', $video->getTitle());
    }

    public function testVideoUrlGetterSetter(): void
    {
        $video = new Video();
        $video->setUrl('https://youtube.com/watch?v=abc');

        $this->assertSame('https://youtube.com/watch?v=abc', $video->getUrl());
    }

    public function testVideoUrlCanBeNull(): void
    {
        $video = new Video();
        $video->setUrl(null);

        $this->assertNull($video->getUrl());
    }

    public function testVideoIsInstanceOfMedia(): void
    {
        $video = new Video();

        $this->assertInstanceOf(\App\Entity\Media::class, $video);
    }

    public function testVideoSetterReturnsStatic(): void
    {
        $video = new Video();
        $result = $video->setUrl('https://youtube.com');

        $this->assertInstanceOf(Video::class, $result);
    }
}
