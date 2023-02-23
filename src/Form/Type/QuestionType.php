<?php

namespace Sherlockode\SyliusFAQPlugin\Form\Type;

use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Core\Model\Channel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            ->add('category', EntityType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.category',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('position', NumberType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.position'
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => QuestionTranslationType::class,
                'label' => false,
            ])
            ->add('channels', EntityType::class, [
                'class' => Channel::class,
                'label' => 'sherlockode_sylius_faq.ui.question.channels',
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
