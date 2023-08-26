<?php

namespace KzykHys\Steganography;

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
     * @param CompressorInterface $compressor
     * @param array               $options
     *
     * @return mixed
     */
    public function encode($data, CompressorInterface $compressor, array $options = []);

    /**
     * Decode a message
     *
     * @param string              $data
     * @param CompressorInterface $compressor
     * @param array               $options
     *
     * @return mixed
     */
    public function decode($data, CompressorInterface $compressor, array $options = []);

    /**
     * Configure default options
     *
     * @param OptionsResolver $resolver
     *
     * @return EncoderInterface
     */
    public function setDefaultOptions(OptionsResolver $resolver);

} 
