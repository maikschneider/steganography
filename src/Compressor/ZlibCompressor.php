<?php

namespace MaikSchneider\Steganography\Compressor;

use MaikSchneider\Steganography\CompressorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
class ZlibCompressor extends Compressor
{

    /**
     * {@inheritdoc}
     */
    public function compress($data): mixed
    {
        return gzcompress($data, $this->options['level']);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress($data): string
    {
        return gzuncompress($data);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported(): bool
    {
        return function_exists('gzcompress');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver): CompressorInterface
    {
        $resolver->setDefaults([
            'level' => -1
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'zlib';
    }

}
