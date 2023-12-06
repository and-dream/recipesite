<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This controller display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    // ici on fait une injection de dépendance de IngredientRepository

    {
        // $ingredients = $repository->findAll();     //on appelle la fonction findAll()
        // dd($ingredients);


        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        // on va passer une variable "ingredients" : on va la passer à la VUE
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
         ]);
    }

    // création du formulaire 

   /**
    * This controller show a form which create an ingredient
    *
    * @param Request $request
    * @param EntityManagerInterface $manager
    * @return Response
    */
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
       $ingredients = new Ingredient();
       $form = $this->createForm(IngredientType::class, $ingredients);

       $form->handleRequest($request);
    //    dd($form);
       if ($form->isSubmitted() && $form->isValid()){
        // dd($form->getData());
        $ingredient = $form->getData();
        // dd($ingredient);

        $manager->persist($ingredient);
        $manager->flush();
 
        $this->addFlash(
            'success',
            'Votre ingrédient a été créé avec succès !'
        );

        return $this->redirectToRoute('ingredient_index');

       }
       
    //    on va passer en paramètres le formulaire
        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/edition/{id}', name : 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient,
    Request $request,
    EntityManagerInterface $manager
    ) : Response
   {

    $form = $this->createForm(IngredientType::class, $ingredient);

    $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid()){
     
     $ingredient = $form->getData();
     

     $manager->persist($ingredient);
     $manager->flush();

     $this->addFlash(
         'success',
         'Votre ingrédient a été modifié avec succès !'
     );

     return $this->redirectToRoute('ingredient_index');

    }
    
 //    on va passer en paramètres le formulaire
     return $this->render('pages/ingredient/edit.html.twig', [
         'form' => $form->createView()
     ]);
   }
   

#[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
public function delete(EntityManagerInterface $manager, Ingredient $ingredient) : Response
{
    if(!$ingredient) {
        $this->addFlash(
            'warning',
            'L\'ingrédient n\'a pas été trouvé !'
        );
        return $this->redirectToRoute('ingredient_index');
    }
    
    $manager->remove($ingredient);
    $manager->flush();

    $this->addFlash(
        'success',
        'Votre ingrédient a été supprimé avec succès !'
    );

    return $this->redirectToRoute('ingredient_index');
}




}
