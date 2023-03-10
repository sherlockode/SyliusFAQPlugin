<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sherlockode_faq_category")
 */
class Category implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Collection|ChannelInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Core\Model\Channel")
     * @ORM\JoinTable(name="sherlockode_faq_categories_channels",
     *     joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="channel_id", referencedColumnName="id")}
     * )
     */
    private $channels;

    /**
     * @var Collection|Question[]
     *
     * @ORM\OrderBy({"position": "ASC"})
     * @ORM\OneToMany(targetEntity="Sherlockode\SyliusFAQPlugin\Entity\Question", mappedBy="category", orphanRemoval=true)
     */
    private $questions;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var \SplFileInfo|null
     */
    private $icon;

    /**
     * @var string|null
     *
     * @ORM\Column(name="icon_path", type="string", nullable=true)
     */
    private $iconPath;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->channels = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ChannelInterface[]
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return $this
     */
    public function addChannel(ChannelInterface $channel): self
    {
        $this->channels->add($channel);

        return $this;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return $this
     */
    public function removeChannel(ChannelInterface $channel): self
    {
        $this->channels->removeElement($channel);

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     *
     * @return $this
     */
    public function addQuestion(Question $question): self
    {
        $this->questions->add($question);
        $question->setCategory($this);

        return $this;
    }

    /**
     * @param Question $question
     *
     * @return $this
     */
    public function removeQuestion(Question $question): self
    {
        $this->questions->removeElement($question);
        $question->setCategory(null);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return $this
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \SplFileInfo|null
     */
    public function getIcon(): ?\SplFileInfo
    {
        return $this->icon;
    }

    /**
     * @param \SplFileInfo|null $icon
     *
     * @return Category
     */
    public function setIcon(?\SplFileInfo $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconPath(): ?string
    {
        return $this->iconPath;
    }

    /**
     * @param string|null $iconPath
     *
     * @return $this
     */
    public function setIconPath(?string $iconPath): self
    {
        $this->iconPath = $iconPath;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name): Category
    {
        $this->getTranslation()->setName($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): CategoryTranslation
    {
        return new CategoryTranslation();
    }

    /**
     * @param string|null $locale
     *
     * @return CategoryTranslation
     */
    public function getTranslation(?string $locale = null): CategoryTranslation
    {
        /** @var CategoryTranslation $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }
}
