<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextEditorField::new('content')
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
            ]); // Ajout de la classe 'ckeditor' si nécessaire
        yield AssociationField::new('categories')
            ->setFormTypeOption('multiple', true)
            ->setFormTypeOption('expanded', true)
            ->formatValue(function ($value, $entity) {
                /** @var Article $entity */
                return implode(', ', $entity->getCategories()->map(function ($category) {
                    return $category->getTitle();
                })->toArray());
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setSearchFields(['title', 'content', 'categories.name']);
    }

    private function getExcerpt($content,$lines): string
    {
        $linesArray = explode("\n", strip_tags($content));
        $excerpt = array_slice($linesArray, 0, $lines);
        return implode("\n", $excerpt) . (count($linesArray) > $lines ? '...' : '');
    }
}