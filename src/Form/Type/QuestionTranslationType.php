<?php

namespace Sherlockode\SyliusFAQPlugin\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sherlockode\SyliusFAQPlugin\Entity\QuestionTranslation;
use Sherlockode\SyliusFAQPlugin\Validator\Constraints\Length as CKeditorLength;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'constraints' => [
                    new Length(min: 2, max: 255, groups: ['sylius'])
                ],
            ])
            ->add('answer', CKEditorType::class, [
                'label' => 'sherlockode_sylius_faq.ui.question.answer',
                'constraints' => [
                    new CKEditorLength(min: 2, groups: ['sylius'])
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if ('' !== $data['question'] || '' !== $data['answer']) {
                $event->getForm()
                    ->add('question', TextType::class, [
                        'label' => 'sherlockode_sylius_faq.ui.question.question',
                        'constraints' => [
                            new NotBlank(groups: ['sylius']),
                            new Length(min: 2, max: 255, groups: ['sylius']),
                        ],
                    ])
                    ->add('answer', CKEditorType::class, [
                        'label' => 'sherlockode_sylius_faq.ui.question.answer',
                        'constraints' => [
                            new NotBlank(groups: ['sylius']),
                            new CKEditorLength(min: 8, groups: ['sylius'])
                        ],
                    ])
                ;
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => QuestionTranslation::class,
        ]);
    }
}
