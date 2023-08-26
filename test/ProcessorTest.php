<?php

namespace MaikSchneider\Steganography\Test;

namespace MaikSchneider\Steganography\Test;

use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use MaikSchneider\Steganography\Image;
use MaikSchneider\Steganography\Processor;
use MaikSchneider\Steganography\Test\Resources\stub\InvalidCompressor;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{

    public function testCompressor()
    {
        $processor = new Processor();
        $processor->setCompressor(new ZlibCompressor());
        $this->assertInstanceOf('MaikSchneider\\Steganography\\CompressorInterface', $processor->getCompressor());
    }

    public function testEncode()
    {
        $message = 'a48995845f83ee779c632fd1225224e0e07380fc61da8f495f3e25760fc0e0029034ca41960adb81aeceee4902a1163b';

        $processor = new Processor();
        $image = $processor->encode(__DIR__ . '/Resources/img/koala.jpg', $message);
        $image->write(__DIR__ . '/Resources/out/koala_out.png');

        $this->assertFileExists(__DIR__ . '/Resources/out/koala_out.png');

        return $message;
    }

    /**
     * @param string $expected
     * @depends testEncode
     */
    public function testDecode(string $expected)
    {
        $processor = new Processor();
        $message = $processor->decode(__DIR__ . '/Resources/out/koala_out.png');

        $this->assertEquals($expected, $message);
    }

    public function testEncoder()
    {
        $processor = new Processor();
        $processor->setEncoder(new \MaikSchneider\Steganography\Encoder\DefaultEncoder());

        $this->assertInstanceOf('MaikSchneider\\Steganography\\EncoderInterface', $processor->getEncoder());
    }

    public function testInvalidCompressor()
    {
        $this->expectException(\RuntimeException::class);

        $processor = new Processor();
        $processor->setCompressor(new InvalidCompressor());
    }

} 