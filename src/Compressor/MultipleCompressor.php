<?php

namespace MaikSchneider\Steganography\Compressor;

use LogicException;
use MaikSchneider\Steganography\CompressorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kazuyuki Hayashi
 */
class MultipleCompressor extends Compressor
{

    /**
     * @var CompressorInterface[]
     */
    private array $children = [];

    /**
     * @var CompressorInterface
     */
    private $selectedCompressor;

    /**
     * @return $this
     */
    public function attach(CompressorInterface $compressor)
    {
        $this->children[$compressor->getName()] = $compressor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compress($data): mixed
    {
        if (!$this->selectCompressor()) {
            throw new LogicException('Attach at least 1 compressor');
        }

        return $this->selectedCompressor->compress($data);
    }

    /**
     * {@inheritdoc}
     */
    public function decompress($data): string
    {
        if (!$this->selectCompressor()) {
            throw new LogicException('Attach at least 1 compressor');
        }

        return $this->selectedCompressor->decompress($data);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver): CompressorInterface
    {
        $resolver->setDefaults([
            'preferred_choice' => null
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported(): bool
    {
        return $this->selectCompressor();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'multiple';
    }

    protected function selectCompressor(): bool
    {
        if ($this->selectedCompressor) {
            return true;
        }

        if (isset($this->children[$this->options['preferred_choice']])) {
            $this->selectedCompressor = $this->children[$this->options['preferred_choice']];

            if ($this->selectedCompressor->isSupported()) {
                return true;
            }
        }

        foreach ($this->children as $compressor) {
            if ($compressor->isSupported()) {
                $this->selectedCompressor = $compressor;

                return true;
            }
        }

        return false;
    }

}
