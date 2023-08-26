<?php

namespace MaikSchneider\Steganography\Test\Compressor;

use MaikSchneider\Steganography\Compressor\MultipleCompressor;
use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use MaikSchneider\Steganography\Test\Resources\stub\InvalidCompressor;
use PHPUnit\Framework\TestCase;

class MultipleCompressorTest extends TestCase
{

    public function testName(): void
    {
        $compressor = new MultipleCompressor();
        $compressor->attach(new ZlibCompressor());
        $this->assertEquals('multiple', $compressor->getName());
    }

    public function testIsSupported(): void
    {
        $compressor = new MultipleCompressor();
        $compressor->attach(new ZlibCompressor());
        $this->assertTrue($compressor->isSupported());

        $compressor = new MultipleCompressor();
        $this->assertFalse($compressor->isSupported());
    }

    public function testEncodeAndDecode(): void
    {
        $compressor = new MultipleCompressor();
        $compressor->attach(new ZlibCompressor());

        $compressed = $compressor->compress('test');

        $this->assertEquals('test', $compressor->decompress($compressed));
    }

    /**
     * @expectedException LogicException
     */
    public function testEncodeBeforeAttach(): void
    {
        $this->expectException(\LogicException::class);

        $compressor = new MultipleCompressor();
        $compressor->compress('test');
    }

    /**
     * @expectedException LogicException
     */
    public function testDecodeBeforeAttach(): void
    {
        $compressor = new MultipleCompressor();
        $compressor->decompress('test');
    }

    public function testPreferredChoice(): void
    {
        require_once __DIR__ . '/../Resources/stub/InvalidCompressor.php';

        $compressor = new MultipleCompressor(['preferred_choice' => 'zlib']);
        $compressor->attach(new ZlibCompressor());
        $compressor->attach(new InvalidCompressor());
        $compressor->compress('test');
    }

    public function testInvalidPreferredChoice(): void
    {
        require_once __DIR__ . '/../Resources/stub/InvalidCompressor.php';

        $compressor = new MultipleCompressor(['preferred_choice' => 'invalid']);
        $compressor->attach(new ZlibCompressor());
        $compressor->attach(new InvalidCompressor());
        $compressor->compress('test');
    }

} 