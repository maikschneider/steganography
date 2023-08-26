<?php

namespace MaikSchneider\Steganography\Test\Compressor;

use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use PHPUnit\Framework\TestCase;

class ZlibCompressorTest extends TestCase
{

    public function testName()
    {
        $compressor = new ZlibCompressor();
        $this->assertEquals('zlib', $compressor->getName());
    }

    public function testEncodeAndDecodeWithDefaultLevel()
    {
        $compressor = new ZlibCompressor();
        $compressed = $compressor->compress('test');

        $this->assertEquals('test', $compressor->decompress($compressed));
    }

    public function testEncodeAndDecodeWithHigherLevel()
    {
        $compressor = new ZlibCompressor(['level' => 6]);
        $compressed = $compressor->compress('test');

        $this->assertEquals('test', $compressor->decompress($compressed));
    }

} 