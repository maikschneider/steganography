<?php

namespace MaikSchneider\Steganography;

use LogicException;
use RuntimeException;
use MaikSchneider\Steganography\Compressor\ZlibCompressor;
use MaikSchneider\Steganography\Encoder\DefaultEncoder;
use MaikSchneider\Steganography\Image\Image;
use MaikSchneider\Steganography\Iterator\BinaryIterator;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
class Processor
{

    final const BITS_PER_PIXEL = 3;

    final const LENGTH_BITS    = 48;

    private ZlibCompressor|CompressorInterface $compressor;

    /**
     * @var EncoderInterface
     */
    private DefaultEncoder $encoder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->encoder    = new DefaultEncoder();
        $this->compressor = new ZlibCompressor();
    }

    /**
     * @param string $file
     * @param string $message
     *
     * @throws LogicException
     */
    public function encode($file, $message, array $options = []): Image
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

    /**
     * @param string $file
     *
     * @return mixed
     */
    public function decode($file, array $options = [])
    {
        $image  = new Image($file);
        $binary = $image->getBinaryString();

        return $this->decodeMessage($binary, $options);
    }

    /**
     *
     * @throws RuntimeException
     * @return $this
     */
    public function setCompressor(CompressorInterface $compressor)
    {
        if (!$compressor->isSupported()) {
            throw new RuntimeException('Unsupported type of compressor: ' . $compressor->getName());
        }

        $this->compressor = $compressor;

        return $this;
    }

    public function getCompressor(): CompressorInterface
    {
        return $this->compressor;
    }

    /**
     * @param EncoderInterface $encoder
     *
     * @return $this
     */
    public function setEncoder(DefaultEncoder $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * @return EncoderInterface
     */
    public function getEncoder(): DefaultEncoder
    {
        return $this->encoder;
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    protected function encodeMessage($message, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->encoder->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);

        return $this->encoder->encode($message, $this->compressor, $options);
    }

    /**
     * @param string $binary
     *
     * @return mixed
     */
    protected function decodeMessage($binary, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->encoder->setDefaultOptions($resolver);
        $options = $resolver->resolve($options);

        return $this->encoder->decode($binary, $this->compressor, $options);
    }

} 