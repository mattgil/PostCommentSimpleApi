<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 31.05.17
 * Time: 23:24
 */

namespace AppBundle\Form;

use AppBundle\DTO\RegisterUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegisterUserDTO::class,
            'csrf_protection' => false,
            'error_bubbling' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return "register_user";
    }
}
