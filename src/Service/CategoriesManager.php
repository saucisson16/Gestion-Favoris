<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoriesManager
{
    private $em;
    private $session;
    private $validator;

    public function __construct(EntityManagerInterface $em,
                                SessionInterface $session,
                                ValidatorInterface $validator
                                )
    {
        $this->em = $em;
        $this->session = $session;
        $this->validator = $validator;
    }

    /* Gestion des catégories */

    public function getCategories() {
        // Récupération de la liste de toutes les catégories depuis le repository
        $categories = $this->em->getRepository('App:Category')->findAll();

        // Retourne la liste des catégories
        return $categories;
    }

    /* Gestion des articles */
    public function getCategory($id) {
        // Récupération d'un article par son id
        $categoy = $this->em->getRepository('App:Category')->findOneBy(array('id' => $id));
        // Retourne l'article récupéré
        return $categoy;
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

    public function deleteCategory($id) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($id);
        // Supression de la catégorie
        $this->em->remove($category);
        $this->em->flush();
    }

    /**
     * Valide la category
     * @param Category $category
     * @return bool|string
     */
    public function validateCategory(Category $category)
    {
        $errors = $this->validator->validate($category);
        if (count($errors) > 0) {
            $errorsString = "";
            foreach ($errors as $error) {
                $errorsString .=$error->getmessage().'<br>';
            }
            return $errorsString;
        }
        return true;
    }
}
