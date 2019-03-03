<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LinksManager;

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
}
