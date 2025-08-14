<?php
namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $b, array $o)
    {
        $b->add('firstName', TextType::class, ['label'=>'Imię'])
          ->add('lastName',  TextType::class, ['label'=>'Nazwisko'])
          ->add('email',     EmailType::class, ['label'=>'Email']);
    }
    public function configureOptions(OptionsResolver $r) { $r->setDefaults(['data_class'=>Client::class]); }
}
