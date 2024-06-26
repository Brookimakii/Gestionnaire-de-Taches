<?php

	namespace App\Controller;

	use App\Entity\Task;
	use App\Entity\TaskList;
	use App\Form\TaskType;
	use App\Repository\TaskRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Doctrine\ORM\EntityManagerInterface;

	class TaskController extends AbstractController {
		private $entityManager;

		public function __construct(EntityManagerInterface $entityManager) {
			$this->entityManager = $entityManager;
		}

		#[Route('/tasks', name: 'task_list')]
		public function listerTaches(): Response {
			$repository = $this->entityManager->getRepository(Task::class);
			$taches = $repository->findAll();

			return $this->render('task/list.html.twig', [
				'taches' => $taches,
			]);
		}

		#[Route('/tasklist/{id}/task/mein', name: 'task_list_mein')]
		public function taskAssignTo(TaskRepository $taskRepository, TaskList $taskList): Response {
			return $this->render('task/list.html.twig', [
				'tasks' => $taskRepository->getTaskAssignTo($this->getUser(), $taskList),
			]);
		}
		#[Route('/tasklist/{id}/task/all', name: 'task_list_all')]
		public function taskFromList(TaskRepository $taskRepository,TaskList $taskList): Response {
			return $this->render('task/index.html.twig', [
				'tasks' => $taskRepository->getTaskFromList($taskList),
			]);
		}

		#[Route('/task/creer', name: 'task_creer')]
		public function creer(Request $request): Response {
			$task = new Task();
			$form = $this->createForm(TaskType::class, $task);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->entityManager->persist($task);
				$this->entityManager->flush();

				return $this->redirectToRoute('task_detail', ['id' => $task->getId()]);
			}

			return $this->render('task/creation.html.twig', [
				'form' => $form->createView(),
			]);
		}

		#[Route('/task/{id}', name: 'task_detail')]
		public function detail(Task $task): Response {
			return $this->render('task/details.html.twig', [
				'task' => $task,
			]);
		}

		#[Route('/task/{id}/modifier', name: 'task_modifier')]
		public function modifier(Request $request, Task $task): Response {
			$form = $this->createForm(TaskType::class, $task);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->entityManager->flush();

				return $this->redirectToRoute('task_detail', ['id' => $task->getId()]);
			}

			return $this->render('task/modifier.html.twig', [
				'task' => $task,
				'form' => $form->createView(),
			]);
		}
	}
