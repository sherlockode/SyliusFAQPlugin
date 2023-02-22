<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\Uploader;

use enshrined\svgSanitize\Sanitizer;
use Gaufrette\FilesystemInterface;
use Sylius\Component\Core\Filesystem\Adapter\FilesystemAdapterInterface;
use Sylius\Component\Core\Filesystem\Adapter\GaufretteFilesystemAdapter;
use Sylius\Component\Core\Filesystem\Exception\FileNotFoundException;
use Sylius\Component\Core\Generator\ImagePathGeneratorInterface;
use Sylius\Component\Core\Generator\UploadedImagePathGenerator;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private const MIME_SVG_XML = 'image/svg+xml';

    private const MIME_SVG = 'image/svg';

    /** @var Sanitizer */
    protected $sanitizer;

    public function __construct(
        /** @var FilesystemAdapterInterface $filesystem */
        protected FilesystemAdapterInterface|FilesystemInterface $filesystem,
        protected ?ImagePathGeneratorInterface $imagePathGenerator = null,
    ) {
        if ($this->filesystem instanceof FilesystemInterface) {
            @trigger_error(sprintf(
                'Passing Gaufrette\FilesystemInterface as a first argument in %s constructor is deprecated since Sylius 1.12 and will be not possible in Sylius 2.0.',
                self::class,
            ), \E_USER_DEPRECATED);

            /** @psalm-suppress DeprecatedClass */
            $this->filesystem = new GaufretteFilesystemAdapter($this->filesystem);
        }

        if ($imagePathGenerator === null) {
            @trigger_error(sprintf(
                'Not passing an $imagePathGenerator to %s constructor is deprecated since Sylius 1.6 and will be not possible in Sylius 2.0.',
                self::class,
            ), \E_USER_DEPRECATED);
        }

        $this->imagePathGenerator = $imagePathGenerator ?? new UploadedImagePathGenerator();
        $this->sanitizer = new Sanitizer();
    }

    /**
     * @param UploadedFile $image
     *
     * @return string
     * @throws \Exception
     */
    public function upload(UploadedFile $image): string
    {
        $fileContent = $this->sanitizeContent(file_get_contents($image->getPathname()), $image->getMimeType());

        if (null !== $image->getPath() && $this->filesystem->has($image->getPath())) {
            $this->remove($image->getPath());
        }

        do {
            $path = $this->generatePath($image);
        } while ($this->isAdBlockingProne($path) || $this->filesystem->has($path));

        $this->filesystem->write($path, $fileContent);

        return $path;
    }

    public function remove(string $path): bool
    {
        try {
            $this->filesystem->delete($path);
        } catch (FileNotFoundException) {
            return false;
        }

        return true;
    }

    protected function sanitizeContent(string $fileContent, string $mimeType): string
    {
        if (self::MIME_SVG_XML === $mimeType || self::MIME_SVG === $mimeType) {
            $fileContent = $this->sanitizer->sanitize($fileContent);
        }

        return $fileContent;
    }

    /**
     * Will return true if the path is prone to be blocked by ad blockers
     */
    private function isAdBlockingProne(string $path): bool
    {
        return str_contains($path, 'ad');
    }

    /**
     * @param ImageInterface $image
     *
     * @return string
     * @throws \Exception
     */
    private function generatePath(UploadedFile $image): string
    {
        $hash = bin2hex(random_bytes(16));

        return $this->expandPath($hash . '/' . $image->getClientOriginalName());
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function expandPath(string $path): string
    {
        return sprintf('%s/%s/%s', substr($path, 0, 2), substr($path, 2, 2), substr($path, 4));
    }
}
