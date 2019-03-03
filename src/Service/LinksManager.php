<?php
namespace App\Service;
use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface;

class LinksManager
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    //Récupération de tous les articles
    public function getLinks(){
        $post = $this->em->getRepository('App:Link')->findAll();
        return $post;
    }
}
