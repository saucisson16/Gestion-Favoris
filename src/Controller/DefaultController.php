<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LinksManager;
use App\Service\CategoriesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends AbstractController
{
    /**
     * @param LinksManager $linksManager
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(LinksManager $linksManager)
    {
        //Récupération de tous les liens
        $links= $linksManager->getLinks();
        
        return $this->render('default/index.html.twig', [
            'links' => $links,
        ]);
    }

//    /**
//     * @param Request $request
//     * @param CategoriesManager $categoriesManager
//     * @return Response
//     * @throws \Exception
//     *
//     * @Route("/creer-categorie", name="create_category")
//     * @Method("POST")
//     */
//    public function createCategoryAction(Request $request, CategoriesManager $categoriesManager)
//    {
//        if($request->isXmlHttpRequest()) {
//            // Récupération du formulaire de création d'une nouvelle catégorie
//            $createCategory = $categoriesManager->getFormCreateCategory();
//
//            // Hydration de l'entitée avec les valeurs du formulaire
//            $createCategory->handleRequest($request);
//
//            // Soumission du formulaire
//            if ($createCategory->isSubmitted()) {
//
//                // Récupération de l'entitée Category avec les valeurs hydratées
//                $category = $createCategory->getData();
//
//                // récupère le résultat de la validation
//                $validation = $categoriesManager->validateCategory($category);
//                // si la validation n'est pas ok on renvoie les erreurs du validateur
//                if($validation !== true) {
//                    return new Response($validation,500);
//                }
//
//                // Enregistrement de la nouvelle catégorie
//                $categoriesManager->setCategory($category);
//
//                // Rédirection vers le dashboard
//                return new Response("Nouvelle catégorie ajoutée");
//            }
//        }
//        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
//    }

}
