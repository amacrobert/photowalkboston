<?php

namespace App\Admin;

use App\Entity\Image;
use Aws\S3\S3Client;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\{DatagridMapper, ListMapper};
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\{FileType};
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @extends AbstractAdmin<Image>
 */
class ImageAdmin extends AbstractAdmin
{
    public function __construct(
        private S3Client $s3Client,
        private string $uploadsBucket,
        private string $cloudfrontUrl,
        ?string $code = null,
        ?string $class = null,
        ?string $baseControllerName = null
    ) {
        parent::__construct($code, $class, $baseControllerName);
    }

    // Form fields
    protected function configureFormFields(FormMapper $form): void
    {

        $file_options = ['required' => false];
        if ($filename = $this->getSubject()->getFilename()) {
            $file_options['help'] = '<img src="' . $filename . '" style="width: 250px;" />';
            $file_options['help_html'] = true;
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

        // If an object with the same key exists in the bucket already, append a number to the end of the filename
        $originalFilename  =$image->getFile()->getClientOriginalName();
        $filename = $originalFilename;
        $filenameParts = explode('.', $originalFilename, 2);
        $extension = $filenameParts[1] ?? '';

        $i = 2;
        while ($this->s3Client->doesObjectExistV2($this->uploadsBucket, $filename)) {
            $filename = sprintf('%s-%d.%s', $filenameParts[0], $i, $extension);
            $i++;
        }

        $this->s3Client->putObject([
            'Bucket' => $this->uploadsBucket,
            'Key' => $filename,
            'SourceFile' => $image->getFile()->getPathname(),
        ]);

        // set the path property to the filename where you've saved the file
        $image->setFilename($this->cloudfrontUrl . '/' . $filename);

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
