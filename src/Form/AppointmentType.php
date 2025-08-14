<?php
namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('appointmentDate', DateTimeType::class, ['widget'=>'single_text', 'label'=>'Data i godzina'])
          ->add('client', \App\Form\ClientType::class, ['label'=>false]);
    }
    public function configureOptions(OptionsResolver $resolver) { $resolver->setDefaults(['data_class'=>Appointment::class]); }
}
