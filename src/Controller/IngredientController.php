<?php

namespace App\Controller;

use App\Form\IngredientType;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\ArgumentResolver\EntityValueResolver;

class IngredientController extends AbstractController
{
    /**
     * This function is used to display the list of ingredients.
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    /**
     * 
     * This function is used to create a new ingredient.
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/new', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été crée avec succès!'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', ['form' => $form->createView()]);
    }
    /**
     * This function is used to update an ingredient.
     * 
     */
    #[Route('/ingredient/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès!'
            );

            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
     * 
     * This function is used to delete an ingredient.
     */
    #[Route('/ingredient/{id}/delete', name: 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {

        if (!$ingredient) {
            $this->addFlash(
                'warning',
                'Cet ingrédient n\'existe pas!'
            );
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès!'
        );

        return $this->redirectToRoute('ingredient.index');
    }
}
