<?php

namespace Sherlockode\SyliusFAQPlugin\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sherlockode\SyliusFAQPlugin\Entity\QuestionTranslation;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.question',
            ])
            ->add('answer', CKEditorType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.answer',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => QuestionTranslation::class
        ]);
    }
}
