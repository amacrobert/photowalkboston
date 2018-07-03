<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType};
use DateTime;

class EventAdmin extends AbstractAdmin {

    protected $datagridValues = [
        '_sort_by' => 'date',
        '_sort_order' => 'DESC',
    ];

    // Form fields
    protected function configureFormFields(FormMapper $formMapper) {

        $event = $this->getSubject();
        $date_options = ['help' => 'The date and meeting time of the event; for 6:30pm, enter 18:30'];

        if (!$event->getId()) {
            $date_options['data'] = new DateTime('next Wednesday 6:30pm');
        }

        $banner_image_preview = '';
        if ($event->getBannerImage() && $event->getBannerImage()->getFilename()) {
            $src = $event->getBannerImage()->getFilename();
        }

        $formMapper
            ->with('General', ['class' => 'col-md-6'])
                ->add('title', null, [
                    'help' => 'The title of the event, such as "Flowy Primes at Castle Island". This should have the name of the location and reference (even a cryptic one) to any theme or challenge for the week.',
                ])
                ->add('description', null, [
                    'help' => 'A colorful high-level description of the location, event, and themes, and type of shots that people can expect here. Keep it under 100 words.',
                    'attr' => ['style' => 'min-height: 130px']
                ])
                ->add('date', null, $date_options)
                ->add('banner_image', 'Sonata\AdminBundle\Form\Type\ModelListType'/*AdminType::class*/, [
                    'required' => false,
                    'help' => '
                        <p>The image to be used as the banner for the event. It should either showcase the location or the model theme, and could be an image from a previous photo walk at this location or something else.</p>
                        <p>
                            <ul>
                                <li>Landscape orientation</li>
                                <li>Aspect ratio roughly 3:2</li>
                                <li>File size < 600KB</li>
                            </ul>
                        </p>'
                ])
            ->end()

            ->with('Details', ['class' => 'col-md-6'])
                ->add('meeting_location', null, ['help' => 'The exact meeting address. This should be readable by Google Maps.'])
                ->add('parking', null, [
                    'label' => 'Parking Instructions',
                    'help' => 'If desired, describe where to park'
                ])
                ->add('model_theme', null, ['help' => 'If there is a theme for the models, enter it here. Be sure to include options for both female and male models. There will be text stating that themes are optional with this.'])
                ->add('photographer_challenge', null, ['help' => 'If there is a challenge for photographers, enter it here. There will be text stating that challenges are optional with this.'])
            ->end()
        ;
    }

    // Filter fields
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
            ->add('title')
            ->add('date')
        ;
    }

    // List view fields
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title')
            ->add('date')
            ->add('banner_image.filename', 'string', [
                'label' => 'Banner',
                'template' => 'list_image.html.twig'
            ])
        ;
    }
}
