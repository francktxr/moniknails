<?php

namespace App\Form;

use App\Entity\Tarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prestation', TextType::class)
            ->add('details', TextType::class)
            ->add('prix', MoneyType::class, array(
				'constraints' => array(
					new Assert\Type(array(
						'type' => 'double',
						'message' => 'Veuillez saisir un chiffre à virgule (ex : 10.99)',
					)),
				),
				'attr' => array(
					'placeholder' => 'ex:10.99',
					'id' => 'champs-prix',
					'class' => 'form-control'
				),
			))
            ->add('Ajouter', SubmitType::class, array(
				'attr' => array(
					'class' => 'btn btn-success btn-block'
				),
			))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tarif::class,
            'attr' => array(
				'novalidate' => 'novalidate'
			)
        ]);
    }
}
