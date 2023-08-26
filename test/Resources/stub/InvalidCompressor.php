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
     *
     * @return mixed
     */
    public function compress($data)
    {
        return $data;
    }

    /**
     * Uncompress a compressed string
     *
     * @param mixed $data
     *
     * @return string
     */
    public function decompress($data)
    {
        return $data;
    }

    /**
     * @param OptionsResolver $resolver
     * @return \MaikSchneider\Steganography\CompressorInterface
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isSupported()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invalid';
    }

}
