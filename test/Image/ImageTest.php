<?php

namespace MaikSchneider\Steganography\Test\Image;

use InvalidArgumentException;
use MaikSchneider\Steganography\Image\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    public function testSize()
    {
        $image = new Image(__DIR__ . '/../Resources/img/3.jpg');
        $this->assertEquals(3, $image->getWidth());
        $this->assertEquals(3, $image->getHeight());
    }

    public function testInvalidPath()
    {
        $this->expectException(InvalidArgumentException::class);
        new Image(__DIR__.'/foo.jpg');
    }

} 