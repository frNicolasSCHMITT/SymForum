<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;

    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', "Article title"),
            TextField::new('picture', "Image source (link)")->onlyOnForms(),
            TextEditorField::new('content', "Article content")->formatValue(function ($value) { return $value; }),
            DateTimeField::new('publicationDate', "Publication date"),
            DateTimeField::new('lastUpdateDate', "Last update date"),
            AssociationField::new('User'),
            BooleanField::new('isPublished', "Published ?"),
        ];
    }
    
}
