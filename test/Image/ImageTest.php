<?php

namespace MaikSchneider\Steganography\Test\Image;

use InvalidArgumentException;
use MaikSchneider\Steganography\Image\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testSize(): void
    {
        $image = Image::getFromFilePath(__DIR__ . '/../Resources/img/3.jpg');
        self::assertEquals(3, $image->getWidth());
        self::assertEquals(3, $image->getHeight());
    }

    public function testInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Image::getFromFilePath(__DIR__ . '/foo.jpg');
    }
}
