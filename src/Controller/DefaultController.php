<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LinksManager;
use App\Service\CategoriesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Category;
use App\Form\Type\CreateCategoryType;
use App\Form\Type\UpdateCategoryType;

class DefaultController extends AbstractController
{
    /**
     * @param LinksManager $linksManager
     * @param CategoriesManager $categoriesManager
     * @param Request $request
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(LinksManager $linksManager, CategoriesManager $categoriesManager, Request $request)
    {
        //Récupération de tous les liens
        $links= $linksManager->getLinks();

        //Récupération des catégories
        $categories= $categoriesManager->getCategories();

        return $this->render('default/index.html.twig', array(
            'links' => $links,
            'categories' => $categories,
        ));
    }

    /**
     * @param Request $request
     * @param CategoriesManager $categoriesManager
     * @param Request $request
     * @return Response
     * @Route("/homepage/creer-categorie", name="create_category")
     * @Method("POST")
     */
    public function createCategoryAction(Request $request, CategoriesManager $categoriesManager)
    {
            $category = new Category();

            // Récupération du formulaire de création d'une nouvelle catégorie
            $createCategoryForm = $this->get('form.factory')->create(CreateCategoryType::class, $category);

            // Hydration de l'entitée avec les valeurs du formulaire
            $createCategoryForm->handleRequest($request);

            // Soumission du formulaire
            if ($createCategoryForm->isSubmitted()) {

                // Récupération de l'entitée Category avec les valeurs hydratées
                $category = $createCategoryForm->getData();

                // récupère le résultat de la validation
                $validation = $categoriesManager->validateCategory($category);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }

                // Enregistrement de la nouvelle catégorie
                $categoriesManager->setCategory($category);
                $this->addFlash(
                    'success',
                    'Catégorie crée!'
                );
                // Redirect to admin home page
                return $this->redirectToRoute('homepage');
            }
        return $this->render('default/createCategory.html.twig', array(
            'createCategoryForm' => $createCategoryForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @param CategoriesManager $categoriesManager
     * @return Response
     * @throws \Exception
     *
     * @Route("/categorie/{id}/suppression", name="category_delete")
     * @Method("GET")
     */
    public function deleteCategoryAction(Request $request, $id, CategoriesManager $categoriesManager) {
        // Supression de la catégorie
        $categoriesManager->deleteCategory($id);
        // Redirect to admin home page
        return $this->redirectToRoute('homepage');
        }

    /* Gestion des catégories */

    /**
     * @param $id
     * @param CategoriesManager $categoriesManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/categorie/{id}/edition/", name="edit_category")
     * @Method({"GET", "POST"})
     */
    public function editCategoryAction($id, CategoriesManager $categoriesManager, Request $request) {

        // récupère la categorie
        $category = $categoriesManager->getCategory($id);

        // Récupération du formulaire de création d'une nouvelle catégorie
        $updateCategoryForm= $this->get('form.factory')->create(UpdateCategoryType::class, $category);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateCategoryForm->handleRequest($request);
        // teste si la requete est en POST et si les données sont valides
        if($updateCategoryForm->isSubmitted()) {
            // Récupération de l'entitée Catégory avec les valeurs hydratées
            $category = $updateCategoryForm->getData();

            // Valide la question/réponse et récupère les erreurs de formulaire si il y en a
            $validation = $categoriesManager->validateCategory($category);
            // si la validation n'est pas ok on renvoie les erreurs du validateur
            if($validation !== true) {
                return new Response($validation,500);
            }
            // Enregistrement de la nouvelle catégorie
            $categoriesManager->setUpdateCategory($category);
            // Redirect to admin home page
            return $this->redirectToRoute('homepage');
        }
        // renvoie le formulaire d'ajout pour l'affichage en JS
        return $this->render('default/editCategory.html.twig', array(
            'updateCategoryForm' => $updateCategoryForm->createView(),
            'category' => $category
        ));
        }
}
