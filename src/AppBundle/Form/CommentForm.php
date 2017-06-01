<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 22:06
 */

namespace AppBundle\Form;


use AppBundle\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentDTO::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return "add_comment";
    }
}