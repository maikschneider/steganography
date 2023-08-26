<?php

namespace MaikSchneider\Steganography\Image;

use GdImage;
use InvalidArgumentException;
use MultipleIterator;
use LimitIterator;
use RuntimeException;
use MaikSchneider\Steganography\Iterator\BinaryIterator;
use MaikSchneider\Steganography\Iterator\RectIterator;
use MaikSchneider\Steganography\Processor;

/**
 * @author Kazuyuki Hayashi
 */
class Image
{

    private readonly string $path;

    private GdImage|bool|null $image = null;

    private int $width = 0;

    private int $height = 0;

    private int $pixels = 0;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException('File Not Found: ' . $path);
        }

        $this->path = $path;

        $this->initialize();
    }

    /**
     * @return $this
     */
    public function setBinaryString(BinaryIterator $binary)
    {
        $iterator = new MultipleIterator(MultipleIterator::MIT_NEED_ALL|MultipleIterator::MIT_KEYS_ASSOC);
        $iterator->attachIterator(new RectIterator($this->width, $this->height), 'rect');
        $iterator->attachIterator($binary, 'bin');

        foreach ($iterator as $current) {
            $this->setPixel($current['rect'][0], $current['rect'][1], $current['bin']);
        }

        return $this;
    }

    public function getBinaryString(): string
    {
        $iterator = new RectIterator($this->width, $this->height);
        $length   = '';
        $data     = '';
        $offset   = Processor::LENGTH_BITS / Processor::BITS_PER_PIXEL;

        foreach (new LimitIterator($iterator, 0, $offset) as $value) {
            $length .= $this->getPixel($value[0], $value[1]);
        }

        $bits   = (int) bindec($length);
        $length = (int) ceil($bits / Processor::BITS_PER_PIXEL);

        foreach (new LimitIterator($iterator, $offset, $length) as $value) {
            $data .= $this->getPixel($value[0], $value[1]);
        }

        return substr($data, 0, $bits);
    }

    /**
     * @param $x
     * @param $y
     * @param $values
     *
     * @return $this
     */
    public function setPixel($x, $y, array $values) {
        $rgb = $this->getRGB($x, $y);

        foreach ($rgb as $name => $value) {
            $rgb[$name] = bindec(substr(decbin($value), 0, -1) . $values[$name]);
        }

        $color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);
        imagesetpixel($this->image, $x, $y, $color);
        imagecolordeallocate($this->image, $color);

        return $this;
    }

    /**
     * @param $x
     * @param $y
     */
    public function getPixel($x, $y): string
    {
        $result = '';
        $rgb = $this->getRGB($x, $y);

        foreach ($rgb as $value) {
            $result .= substr(decbin($value), -1, 1);
        }

        return $result;
    }

    /**
     * @param $path
     */
    public function write($path): bool
    {
        return imagepng($this->image, $path, 0);
    }

    public function render(): bool
    {
        return imagepng($this->image, null, 0);
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getPixels(): int
    {
        return $this->pixels;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->image) {
            imagedestroy($this->image);
        }
    }

    /**
     * @throws RuntimeException
     *
     * @return resource
     */
    protected function initialize()
    {
        $info         = getimagesize($this->path);
        $this->width  = $info[0];
        $this->height = $info[1];
        $this->pixels = $this->width * $this->height;
        $type         = $info[2];

        switch ($type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($this->path);
                break;
            case IMAGETYPE_GIF;
                $this->image = imagecreatefromgif($this->path);
                break;
            case IMAGETYPE_PNG;
                $this->image = imagecreatefrompng($this->path);
                break;
            default:
                throw new RuntimeException('Unsupport image type ' . $type);
        }

        imagealphablending($this->image, false);
    }

    /**
     * @param $x
     * @param $y
     */
    protected function getRGB($x, $y): array {
        $rgb = imagecolorat($this->image, $x, $y);

        return [
            'r' => ($rgb >> 16) & 0xFF,
            'g' => ($rgb >> 8) & 0xFF,
            'b' => $rgb & 0xFF
        ];
    }

} 