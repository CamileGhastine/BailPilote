<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Post;
use App\Enum\ArticleType;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private Post $post;

    protected function setUp(): void
    {
        $this->post = new Post();
    }

    public function testTitleGetterSetter(): void
    {
        $this->post->setTitle('Mon article');

        $this->assertSame('Mon article', $this->post->getTitle());
    }

    public function testSlugGetterSetter(): void
    {
        $this->post->setSlug('mon-article');

        $this->assertSame('mon-article', $this->post->getSlug());
    }

    public function testDescriptionGetterSetter(): void
    {
        $this->post->setDescription('Résumé de l\'article.');

        $this->assertSame('Résumé de l\'article.', $this->post->getDescription());
    }

    public function testDescriptionCanBeNull(): void
    {
        $this->post->setDescription(null);

        $this->assertNull($this->post->getDescription());
    }

    public function testTypeArticle(): void
    {
        $this->post->setType(ArticleType::Article);

        $this->assertSame(ArticleType::Article, $this->post->getType());
        $this->assertSame('article', $this->post->getType()->value);
    }

    public function testTypeTutoriel(): void
    {
        $this->post->setType(ArticleType::Tutoriel);

        $this->assertSame(ArticleType::Tutoriel, $this->post->getType());
        $this->assertSame('tutoriel', $this->post->getType()->value);
    }

    public function testCreatedAtGetterSetter(): void
    {
        $date = new \DateTimeImmutable('2026-01-01');
        $this->post->setCreatedAt($date);

        $this->assertSame($date, $this->post->getCreatedAt());
    }

    public function testUpdatedAtGetterSetter(): void
    {
        $date = new \DateTimeImmutable('2026-01-01');
        $this->post->setUpdatedAt($date);

        $this->assertSame($date, $this->post->getUpdatedAt());
    }

    public function testPublishedAtGetterSetter(): void
    {
        $date = new \DateTimeImmutable('2026-01-01');
        $this->post->setPublishedAt($date);

        $this->assertSame($date, $this->post->getPublishedAt());
    }

    public function testPublishedAtCanBeNull(): void
    {
        $this->post->setPublishedAt(null);

        $this->assertNull($this->post->getPublishedAt());
    }

    public function testIsPublishedWhenPublishedAtIsSet(): void
    {
        $this->post->setPublishedAt(new \DateTimeImmutable('-1 day'));

        $this->assertNotNull($this->post->getPublishedAt());
    }

    public function testIsNotPublishedWhenPublishedAtIsNull(): void
    {
        $this->post->setPublishedAt(null);

        $this->assertNull($this->post->getPublishedAt());
    }

    public function testCategoryGetterSetter(): void
    {
        $category = new Category();
        $category->setTitle('Droit immobilier');

        $this->post->setCategory($category);

        $this->assertSame($category, $this->post->getCategory());
    }

    public function testCategoryCanBeNull(): void
    {
        $this->post->setCategory(null);

        $this->assertNull($this->post->getCategory());
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->post->getId());
    }

    public function testMediaCollectionIsEmptyByDefault(): void
    {
        $this->assertCount(0, $this->post->getMedia());
    }

    public function testSetterReturnsStatic(): void
    {
        $result = $this->post->setTitle('Test');

        $this->assertInstanceOf(Post::class, $result);
    }
}
