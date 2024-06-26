<?php

	namespace App\Controller;

	use App\Entity\TaskList;
	use App\Form\ListTaskType;
	use App\Repository\TaskListRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Doctrine\ORM\EntityManagerInterface;

	class TaskListController extends AbstractController {
		private $entityManager;

		public function __construct(EntityManagerInterface $entityManager) {
			$this->entityManager = $entityManager;
		}

		#[Route('/tasklists', name: 'app_task_list_index', methods: ['GET'])]
		public function index(TaskListRepository $taskListRepository): Response {
			return $this->render('task_list/index.html.twig', [
				'task_lists' => $taskListRepository->findAll(),
			]);
		}

		#[Route('/new', name: 'app_task_list_new', methods: ['GET', 'POST'])]
		public function new(Request $request, EntityManagerInterface $entityManager): Response {
			$taskList = new TaskList();
			$form = $this->createForm(TaskListType::class, $taskList);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->persist($taskList);
				$entityManager->flush();

				return $this->redirectToRoute('app_task_list_index', [], Response::HTTP_SEE_OTHER);
			}

			return $this->render('task_list/new.html.twig', [
				'task_list' => $taskList,
				'form' => $form,
			]);
		}

		#[Route('/{id}', name: 'app_task_list_show', methods: ['GET'])]
		public function show(TaskList $taskList): Response {
			return $this->render('task_list/show.html.twig', [
				'task_list' => $taskList,
			]);
		}

		#[Route('/{id}/edit', name: 'app_task_list_edit', methods: ['GET', 'POST'])]
		public function edit(Request $request, TaskList $taskList, EntityManagerInterface $entityManager): Response {
			$form = $this->createForm(TaskListType::class, $taskList);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->flush();

				return $this->redirectToRoute('app_task_list_index', [], Response::HTTP_SEE_OTHER);
			}

			return $this->render('task_list/edit.html.twig', [
				'task_list' => $taskList,
				'form' => $form,
			]);
		}

		#[Route('/{id}', name: 'app_task_list_delete', methods: ['POST'])]
		public function delete(Request $request, TaskList $taskList, EntityManagerInterface $entityManager): Response {
			if ($this->isCsrfTokenValid('delete' . $taskList->getId(), $request->getPayload()->getString('_token'))) {
				$entityManager->remove($taskList);
				$entityManager->flush();
			}
			return $this->redirectToRoute('app_task_list_index', [], Response::HTTP_SEE_OTHER);
		}


		#[Route('/tasklists/private', name: 'app_task_private_list_index', methods: ['GET'])]
		public function personalList(TaskListRepository $taskListRepository): Response {
			return $this->render('tasklist/list.html.twig', [
				'tasks' => $taskListRepository->findPersonalListOfUser($this->getUser()),
			]);
		}

		#[Route('/tasklists/shared', name: 'app_task_shared_list_index', methods: ['GET'])]
		public function sharedList(TaskListRepository $taskListRepository): Response {
			return $this->render('tasklist/list.html.twig', [
				'tasks' => $taskListRepository->findSharedListOfUser($this->getUser()),
			]);
		}

		#[Route('/tasklists', name: 'tasklist_list')]
		public function listerListes(): Response {
			$repository = $this->entityManager->getRepository(TaskList::class);
			$listes = $repository->findAll();

			return $this->render('tasklist/list.html.twig', [
				'listes' => $listes,
			]);
		}

		#[Route('/tasklist/creer', name: 'tasklist_creer')]
		public function creer(Request $request): Response {
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
		public function detail(TaskList $taskList): Response {
			return $this->render('tasklist/detail.html.twig', [
				'taskList' => $taskList,
			]);
		}

		#[Route('/tasklist/{id}/modifier', name: 'tasklist_modifier')]
		public function modifier(Request $request, TaskList $taskList): Response {
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
