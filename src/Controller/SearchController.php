<?php
namespace App\Controller;

use App\Form\SearchType;
use App\Repository\TaskListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(Request $request, TaskListRepository $taskListRepository): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $results = $taskListRepository->searchTaskLists($query);
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }
}
