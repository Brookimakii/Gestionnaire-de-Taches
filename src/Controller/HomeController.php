<?php

	namespace App\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Attribute\Route;

	#[Route('/')]
	class HomeController extends AbstractController {
		#[Route('/', name: 'app', methods: ['GET'])]
		public function index(): ?Response {
			return $this->redirectToRoute('app_task_private_list_index');
		}
	}