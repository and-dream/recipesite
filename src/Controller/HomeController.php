<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //!Route avec un attribut
    //Paramètre 1: le path (le chemin)
    //Paramètre 2: le nom
    //Paramètre 3: les méthodes pour la sécurité (éviter des posts ou des put sur nos routes)
    #[Route('/', name: 'home.index', methods: ['GET'])]  //? => Les navigateurs ne font que la méthode GET
   
    //On créé une fonction qui va RETOURNER UNE RESPONSE
    //*Cette response que l'on va retourner c'est la méthode RENDER qui va nous renvoyer vers un template Twig
    public function index(): Response
    {
                //!Paramètres du render
        //Le chemin vers la view en string, on lui dit 'on veut que cela renvoie vers home.html.twig'
        //La méthode Render() va pointer vers le template home.html.twig
        return $this->render('home.html.twig');  
    
    }
       
} 