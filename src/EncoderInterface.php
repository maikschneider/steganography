<?php

namespace MaikSchneider\Steganography;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
interface EncoderInterface
{

    /**
     * Encode a message
     *
     * @param string $data
     *
     * @return mixed
     */
    public function encode($data, CompressorInterface $compressor, array $options = []);

    /**
     * Decode a message
     *
     * @param string              $data
     *
     * @return mixed
     */
    public function decode($data, CompressorInterface $compressor, array $options = []);

    /**
     * Configure default options
     *
     *
     * @return EncoderInterface
     */
    public function setDefaultOptions(OptionsResolver $resolver);

} 
