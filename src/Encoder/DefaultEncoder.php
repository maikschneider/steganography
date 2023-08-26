<?php

namespace MaikSchneider\Steganography\Encoder;

use MaikSchneider\Steganography\CompressorInterface;
use MaikSchneider\Steganography\EncoderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
class DefaultEncoder implements EncoderInterface
{

    /**
     * {@inheritdoc}
     */
    public function encode($data, CompressorInterface $compressor, array $options = []): mixed
    {
        $compressed = base64_encode((string) $compressor->compress($data));
        $bin        = '';
        $length     = strlen($compressed);

        for ($i = 0; $i < $length; ++$i) {
            $bin .= sprintf('%08b', ord($compressed[$i]));
        }

        return $bin;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, CompressorInterface $compressor, array $options = []): mixed
    {
        $chars  = str_split($data, 8);
        $compressed = '';

        foreach ($chars as $char) {
            $compressed .= chr(bindec($char));
        }

        return $compressor->decompress(base64_decode($compressed));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver): EncoderInterface
    {
        return $this;
    }

}
