<?php

namespace App\Form;

use App\Entity\Juegos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JuegosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Añade aquí todos los campos que tenga tu entidad Juegos
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Juego'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar Juego']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juegos::class,
        ]);
    }
}