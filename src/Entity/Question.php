<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sherlockode_faq_question")
 */
class Question implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
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
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Collection|ChannelInterface[]
     *
     * @ORM\OneToMany(targetEntity="Sylius\Component\Core\Model\Channel")
     */
    private $channels;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->setPosition(1);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->getTranslation()->getQuestion();
    }

    /**
     * @param string|null $question
     *
     * @return $this
     */
    public function setQuestion(?string $question): self
    {
        $this->getTranslation()->setQuestion($question);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->getTranslation()->getAnswer();
    }

    /**
     * @param string|null $answer
     *
     * @return $this
     */
    public function setAnswer(?string $answer): self
    {
        $this->getTranslation()->setAnswer($answer);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new QuestionTranslation();
    }
}
