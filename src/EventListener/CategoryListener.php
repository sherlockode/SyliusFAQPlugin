<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Uploader\ImageUploader;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

class CategoryListener
{
    /**
     * @var ImageUploader
     */
    private $uploader;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var array
     */
    private array $imagesToDelete = [];

    /**
     * @param ImageUploader $uploader
     * @param CacheManager  $cacheManager
     * @param FilterManager $filterManager
     */
    public function __construct(ImageUploader $uploader, CacheManager $cacheManager, FilterManager $filterManager)
    {
        $this->uploader = $uploader;
        $this->cacheManager = $cacheManager;
        $this->filterManager = $filterManager;
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function uploadImage(GenericEvent $event): void
    {
        $subject = $event->getSubject();
        Assert::isInstanceOf($subject, Category::class);

        if (null !== $subject->getIcon()) {
            $path = $this->uploader->upload($subject->getIcon());
            $subject->setIconPath($path);
        }
    }

    /**
     * @param OnFlushEventArgs $event
     *
     * @return void
     */
    public function onFlush(OnFlushEventArgs $event): void
    {
        $uow = $event->getObjectManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Category) {
                continue;
            }

            if (!isset($uow->getEntityChangeSet($entity)['iconPath'])) {
                continue;
            }

            $iconPathValues = $uow->getEntityChangeSet($entity)['iconPath'];

            if (null !== $iconPathValues[0] && $iconPathValues[0] !== $iconPathValues[1]) {
                if (!in_array($iconPathValues[0], $this->imagesToDelete, true)) {
                    $this->imagesToDelete[] = $iconPathValues[0];
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Category) {
                continue;
            }

            if ($entity->getIconPath() === null) {
                continue;
            }

            if (!in_array($entity->getIconPath(), $this->imagesToDelete, true)) {
                $this->imagesToDelete[] = $entity->getIconPath();
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     *
     * @return void
     */
    public function postFlush(PostFlushEventArgs $event): void
    {
        foreach ($this->imagesToDelete as $key => $imagePath) {
            $this->uploader->remove($imagePath);
            $this->cacheManager->remove($imagePath, array_keys($this->filterManager->getFilterConfiguration()->all()));
            unset($this->imagesToDelete[$key]);
        }
    }

    /**
     * @param PrePersistEventArgs $event
     *
     * @return void
     */
    public function prePersist(PrePersistEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof Category) {
            return;
        }

        $category = $event->getObjectManager()->getRepository(Category::class)->findOneBy([], ['position' => 'DESC']);
        if (null === $category) {
            $entity->setPosition(1);

            return;
        }

        $entity->setPosition($category->getPosition() + 1);
    }
}
