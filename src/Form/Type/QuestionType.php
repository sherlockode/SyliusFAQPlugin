<?php

namespace Sherlockode\SyliusFAQPlugin\Form\Type;

use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Core\Model\Channel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => QuestionTranslationType::class,
                'label' => false,
            ])
            ->add('channels', EntityType::class, [
                'class' => Channel::class,
                'by_reference' => false,
                'multiple' => true,
                'choice_label' => 'name',
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
