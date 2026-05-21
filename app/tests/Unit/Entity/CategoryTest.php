<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        $this->category = new Category();
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->category->getId());
    }

    public function testTitleGetterSetter(): void
    {
        $this->category->setTitle('Droit immobilier');

        $this->assertSame('Droit immobilier', $this->category->getTitle());
    }

    public function testTitleIsNullByDefault(): void
    {
        $this->assertNull($this->category->getTitle());
    }

    public function testSetterReturnsStatic(): void
    {
        $result = $this->category->setTitle('Test');

        $this->assertInstanceOf(Category::class, $result);
    }
}
