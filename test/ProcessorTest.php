<?php

namespace MaikSchneider\Steganography\Test;

use MaikSchneider\Steganography\Compressor\CompressorInterface;
use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use MaikSchneider\Steganography\Encoder\DefaultEncoder;
use MaikSchneider\Steganography\Encoder\EncoderInterface;
use MaikSchneider\Steganography\Processor;
use MaikSchneider\Steganography\Test\Resources\stub\InvalidCompressor;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{
    public function testCompressor(): void
    {
        $processor = new Processor();
        $processor->setCompressor(new ZlibCompressor());
        self::assertInstanceOf(CompressorInterface::class, $processor->getCompressor());
    }

    public function testEncode()
    {
        $message = 'a48995845f83ee779c632fd1225224e0e07380fc61da8f495f3e25760fc0e0029034ca41960adb81aeceee4902a1163b';

        $processor = new Processor();
        $image = $processor->encode(__DIR__ . '/Resources/img/koala.jpg', $message);
        $image->write(__DIR__ . '/Resources/out/koala_out.png');

        self::assertFileExists(__DIR__ . '/Resources/out/koala_out.png');

        return $message;
    }

    /**
     * @depends testEncode
     */
    public function testDecode(string $expected): void
    {
        $processor = new Processor();
        $message = $processor->decode(__DIR__ . '/Resources/out/koala_out.png');

        self::assertEquals($expected, $message);
    }

    public function testEncoder(): void
    {
        $processor = new Processor();
        $processor->setEncoder(new DefaultEncoder());

        self::assertInstanceOf(EncoderInterface::class, $processor->getEncoder());
    }

    public function testInvalidCompressor(): void
    {
        $this->expectException(\RuntimeException::class);

        $processor = new Processor();
        $processor->setCompressor(new InvalidCompressor());
    }
}
