<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {


        yield TextField::new('title');
        yield DateTimeField::new('projectDate')->setFormat('MM/yyyy');
        yield ArrayField::new('tags');
        yield TextEditorField::new('description')
            ->setNumOfRows(5)
            ->setFormTypeOption('attr', ['class' => 'ckeditor'])
            ->setTrixEditorConfig([
                'blockAttributes' => [
                    'default' => ['tagName' => 'p'],
                    'heading1' => ['tagName' => 'h2'],
                ],
                'css' => [
                    'attachment' => 'admin_file_field_attachment',
                ],
            ]);
        yield ImageField::new('thumbnail')
            ->setUploadDir('public/img/project')
            ->setBasePath('/Users/mattgroult/projet/perso/portfolio/public/img/project');
        yield UrlField::new('link');
    }
}
