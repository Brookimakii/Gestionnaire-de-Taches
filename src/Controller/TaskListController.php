<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Form\ListTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class TaskListController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/tasklists', name: 'tasklist_list')]
    public function listerListes(): Response
    {
        $repository = $this->entityManager->getRepository(TaskList::class);
        $listes = $repository->findAll();

        return $this->render('tasklist/list.html.twig', [
            'listes' => $listes,
        ]);
    }

    #[Route('/tasklist/creer', name: 'tasklist_creer')]
    public function creer(Request $request): Response
    {
        $taskList = new TaskList();
        $form = $this->createForm(ListTaskType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($taskList);
            $this->entityManager->flush();

            return $this->redirectToRoute('tasklist_detail', ['id' => $taskList->getId()]);
        }

        return $this->render('tasklist/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasklist/{id}', name: 'tasklist_detail')]
    public function detail(TaskList $taskList): Response
    {
        return $this->render('tasklist/detail.html.twig', [
            'taskList' => $taskList,
        ]);
    }

    #[Route('/tasklist/{id}/modifier', name: 'tasklist_modifier')]
    public function modifier(Request $request, TaskList $taskList): Response
    {
        $form = $this->createForm(ListTaskType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('tasklist_detail', ['id' => $taskList->getId()]);
        }

        return $this->render('tasklist/modifier.html.twig', [
            'taskList' => $taskList,
            'form' => $form->createView(),
        ]);
    }
}
