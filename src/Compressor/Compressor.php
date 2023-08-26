<?php

namespace MaikSchneider\Steganography\Compressor;

use MaikSchneider\Steganography\CompressorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
abstract class Compressor implements CompressorInterface
{

    protected array $options;

    /**
     * Constructor
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

} 