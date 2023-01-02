<?php

namespace App\Admin;

use App\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\{FileType};

/**
 * @extends AbstractAdmin<Image>
 */
class ImageAdmin extends AbstractAdmin
{
    // Form fields
    protected function configureFormFields(FormMapper $form): void
    {

        $file_options = ['required' => false];
        if ($filename = $this->getSubject()->getFilename()) {
            $file_options['help'] = '
                <img src="' . $filename . '" style="width: 250px;" />
            ';
        }

        $form
            ->add('file', FileType::class, $file_options)
        ;
    }

    public function upload(?Image $image): ?Image
    {
        if (!$image->getFile()) {
            return null;
        }

        ///////////////////////////////
        // @TODO: Sanitize file here //
        ///////////////////////////////

        // Move image to image dir
        $image->getFile()->move(
            './images/uploads/',
            $image->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $image->setFilename('/images/uploads/' . $image->getFile()->getClientOriginalName());

        return $image;
    }

    public function prePersist($image): void
    {
        $this->upload($image);
    }

    public function preUpdate($image): void
    {
        $this->upload($image);
    }

    // Filter fields
    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
    }

    // List view fields
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('filename', 'string', ['template' => 'list_image.html.twig'])
        ;
    }
}
