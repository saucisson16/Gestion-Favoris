<?php

namespace App\Service;

use App\Entity\Category;
use App\Form\Type\CreateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoriesManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $fileSystem;
    private $validator;
    private $categoriesDirectory;

    public function __construct(FormFactoryInterface $formBuilder,
                                EntityManagerInterface $em,
                                RequestStack $request,
                                SessionInterface $session,
                                Filesystem $filesystem,
                                ValidatorInterface $validator,
                                $categoriesDirectory
                                )
    {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->fileSystem = $filesystem;
        $this->validator = $validator;
        $this->categoriesDirectory = $categoriesDirectory;
    }

    /* Gestion des catégories */

    public function getCategories() {
        // Récupération de la liste de toutes les catégories depuis le repository
        $categories = $this->em->getRepository('App:Category')->findAll();

        // Retourne la liste des catégories
        return $categories;
    }

    public function getCategory($slug) {
        // Récupération de la catégorie par son id depuis le repository
        $category = $this->em->getRepository('App:Category')->findOneBy(array('slug' => $slug));

        // Retourne la catégorie
        return $category;
    }

    public function getFormCreateCategory() {
        // Création d'une nouvelle entitée Category
        $category = new Category();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $form = $this->formBuilder->create(CreateCategoryType::class, $category);

        // Retourne le formulaire
        return $form;
    }

    public function getFormUpdateCategory($slug) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($slug);

        $form = $this->formBuilder->create(UpdateCategoryType::class, $category);

        return $form;
    }

    public function setCategory(Category $category) {

        // Sauvegarde de la nouvelle catégorie
        $this->em->persist($category);

        // Enregistrement de la nouvelle catégorie
        $this->em->flush();
    }

    public function setUpdateCategory(Category $category) {

        // Sauvegarde de la nouvelle catégorie
        $this->em->persist($category);

        // Enregistrement de la nouvelle catégorie
        $this->em->flush();
    }

    public function deleteCategory($slug) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($slug);

        // Vérification si il y a des articles dans cette catégorie
        if (count($category->getLinks()) != 0) {
            // Création du message flash d'erreur
            $this->session->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une catégorie qui possède des articles.');

        }
        else {
            // Supression de la catégorie
            $this->em->remove($category);
            $this->em->flush();
        }
    }
}
