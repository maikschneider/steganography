<?php

namespace MaikSchneider\Steganography;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
interface CompressorInterface
{

    /**
     * Compress a string
     *
     * @param string $data
     *
     * @return mixed
     */
    public function compress($data);

    /**
     * Uncompress a compressed string
     *
     * @param mixed $data
     *
     * @return string
     */
    public function decompress($data);

    /**
     * @return CompressorInterface
     */
    public function setDefaultOptions(OptionsResolver $resolver);

    /**
     * @return bool
     */
    public function isSupported();

    /**
     * @return string
     */
    public function getName();

} 
