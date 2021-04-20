<?php 

namespace AppBundle\Form;

use AppBundle\Entity\ContactList;
use AppBundle\Service\WorldCountries;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $worldCountries = new WorldCountries();

        $builder
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('street', TextType::class)
            ->add('zip', IntegerType::class)
            ->add('city', TextType::class)
            ->add('country', ChoiceType::class, [
                    'choices'  => $worldCountries->allCountries()
                ])
            ->add('phone', IntegerType::class)
            ->add('dob', BirthdayType::class, ['label'=>'Date of Birth', 'format' => 'yyyy-MM-dd'])
            ->add('email', EmailType::class)
            ->add('picture', FileType::class,[
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ]                        
                        ])
                    ]
                ])
            ->add('save', SubmitType::class, ['label' => 'Save/Update'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactList::class,
        ]);
    }
}