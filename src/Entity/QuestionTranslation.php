<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sherlockode_faq_question_translation")
 */
class QuestionTranslation extends AbstractTranslation implements ResourceInterface
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question", type="string")
     */
    private $question;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer", type="text")
     */
    private $answer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string|null $question
     *
     * @return $this
     */
    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @param string|null $answer
     *
     * @return $this
     */
    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
