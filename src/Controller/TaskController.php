<?php

	namespace App\Controller;

	use App\Entity\Task;
	use App\Entity\TaskList;
	use App\Form\TaskType;
	use App\Repository\TaskRepository;
	use App\Repository\UserRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Doctrine\ORM\EntityManagerInterface;

	class TaskController extends AbstractController {
		private EntityManagerInterface $entityManager;

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

		#[Route('/tasklist/{id}/task/mine', name: 'task_list_mein')]
		public function taskAssignTo(TaskRepository $taskRepository, TaskList $taskList): Response {
			return $this->render('task/list.html.twig', [
				'tasks' => $taskRepository->getTaskAssignTo($this->getUser(), $taskList),
				'show_footer' => "task",
				'taskList' => $taskList,
        		'task_list' => $taskList,
			]);
		}

		#[Route('/tasklist/{id}/task/all', name: 'task_list_all')]
		public function taskFromList(TaskRepository $taskRepository,TaskList $taskList): Response {
			return $this->render('task/list.html.twig', [
				'tasks' => $taskRepository->getTaskFromList($taskList),
				'show_footer' => "task",
				'taskList' => $taskList,
				'task_list' => $taskList,
				
			]);
		}


		#[Route('/tasklist/{id}/task/creer', name: 'task_creer')]
		public function creer(Request $request, TaskList $taskList): Response {
			$task = new Task();
			$task->setTaskList($taskList);
			$form = $this->createForm(TaskType::class, $task);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->entityManager->persist($task);
				$this->entityManager->flush();

				return $this->redirectToRoute('task_list_all', ['id' => $task->getTaskList()->getId()]);
			}

			return $this->render('task/creation.html.twig', [
				'form' => $form->createView(),
			]);
		}

		#[Route('/task/{id}', name: 'task_detail')]
		public function detail(Task $task): Response {
			return $this->render('task/details.html.twig', [
				'task' => $task,
				'task_list' => $task->getTaskList(),
			]);
		}

		#[Route('/task/{id}/modifier', name: 'task_modifier')]
		public function modifier(Request $request ,Task $task, UserRepository $userRepository): Response {
			$form = $this->createForm(TaskType::class, $task, );
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->entityManager->flush();

				return $this->redirectToRoute('task_detail', ['id' => $task->getId()]);
			}

			return $this->render('task/edit.html.twig', [
				'task' => $task,
				'assignable' => $userRepository->getUserAssociateToList($task->getTaskList()),
				'form' => $form->createView(),
			]);
		}

		#[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
		public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
		{
			if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
				$entityManager->remove($task);
				$entityManager->flush();
			}

			return $this->redirect($request->headers->get('referer'), Response::HTTP_SEE_OTHER);
		}

		#[Route('/task/{id}/toggle-finished', name: 'task_toggle_finished', methods: ['POST'])]
		public function toggleFinished(Task $task, Request $request): Response {

			$task->setFinished(!$task->isFinished());
			$this->entityManager->flush();

			return $this->redirect($request->headers->get('referer'), Response::HTTP_SEE_OTHER);
		}
	}
