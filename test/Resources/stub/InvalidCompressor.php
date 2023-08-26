<?php

namespace MaikSchneider\Steganography\Test\Resources\stub;

use MaikSchneider\Steganography\Compressor\Compressor;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvalidCompressor extends Compressor
{

    /**
     * Compress a string
     *
     * @param string $data
     */
    public function compress($data): mixed
    {
        return $data;
    }

    /**
     * Uncompress a compressed string
     *
     * @param mixed $data
     */
    public function decompress($data): string
    {
        return $data;
    }

    public function setDefaultOptions(OptionsResolver $resolver): \MaikSchneider\Steganography\CompressorInterface
    {
        return $this;
    }

    public function isSupported(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return 'invalid';
    }

}
