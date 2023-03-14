<?php

namespace Sherlockode\SyliusFAQPlugin\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sherlockode\SyliusFAQPlugin\Form\Listener\ResizeFormListener;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Core\Model\Channel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.category',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'sherlockode_sylius_faq.ui.question.select_category',
                'constraints' => [
                    new NotBlank(groups: ['sylius']),
                ]
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => QuestionTranslationType::class,
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Count(min: 1, groups: ['sylius'], minMessage: 'sherlockode_faq.question.translation_min'),
                ],
            ])
            ->add('channels', EntityType::class, [
                'class' => Channel::class,
                'label' => 'sherlockode_sylius_faq.ui.question.channels',
                'by_reference' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'constraints' => [new Count(min: 1, groups: ['sylius'])],
            ])
        ;

        $builder->get('translations')->addEventSubscriber(new ResizeFormListener());

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if ($this->em->getRepository(Category::class)->findOneBy([]) === null) {
                $event->getForm()->remove('category');
            }
        });
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @return QuestionType
     */
    public function setEm(EntityManagerInterface $em): self
    {
        $this->em = $em;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'validation_groups' => ['sylius'],
        ]);
    }
}
