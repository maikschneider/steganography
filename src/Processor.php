<?php

namespace MaikSchneider\Steganography;

use LogicException;
use RuntimeException;
use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use MaikSchneider\Steganography\Encoder\DefaultEncoder;
use MaikSchneider\Steganography\Image\Image;
use MaikSchneider\Steganography\Iterator\BinaryIterator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Processor
{

    final const BITS_PER_PIXEL = 3;

    final const LENGTH_BITS    = 48;

    private ZlibCompressor|CompressorInterface $compressor;

    private EncoderInterface $encoder;

    public function __construct()
    {
        $this->encoder    = new DefaultEncoder();
        $this->compressor = new ZlibCompressor();
    }

    /**
     * @throws LogicException
     */
    public function encode(string $file, string $message, array $options = []): Image
    {
        $image   = new Image($file);
        $message = $this->encodeMessage($message, $options);
        $pixels  = ceil(strlen((string) $message) / self::BITS_PER_PIXEL + (self::LENGTH_BITS / self::BITS_PER_PIXEL));

        if ($pixels > $image->getPixels()) {
            throw new LogicException('Number of pixels is fewer than ' . $pixels);
        }

        $image->setBinaryString(new BinaryIterator($message));

        return $image;
    }

    public function decode(string $file, array $options = []): mixed
    {
        $image  = new Image($file);
        $binary = $image->getBinaryString();

        return $this->decodeMessage($binary, $options);
    }

    /**
     * @throws RuntimeException
     */
    public function setCompressor(CompressorInterface $compressor): void
    {
        if (!$compressor->isSupported()) {
            throw new RuntimeException('Unsupported type of compressor: ' . $compressor->getName());
        }

        $this->compressor = $compressor;
    }

    public function getCompressor(): CompressorInterface
    {
        return $this->compressor;
    }

    public function setEncoder(EncoderInterface $encoder): void
    {
        $this->encoder = $encoder;
    }

    public function getEncoder(): EncoderInterface
    {
        return $this->encoder;
    }

    protected function encodeMessage(string $message, array $options = []): mixed
    {
        $resolver = new OptionsResolver();
        $this->encoder->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);

        return $this->encoder->encode($message, $this->compressor, $options);
    }

    protected function decodeMessage(string $binary, array $options = []): mixed
    {
        $resolver = new OptionsResolver();
        $this->encoder->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);

        return $this->encoder->decode($binary, $this->compressor, $options);
    }

} 