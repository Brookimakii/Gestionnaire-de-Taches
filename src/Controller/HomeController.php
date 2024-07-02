<?php

	namespace App\Controller;

	use App\Entity\User;
	use App\Form\UserType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Attribute\Route;
	use Symfony\Component\Security\Http\Attribute\CurrentUser;

	#[Route('/')]
	class HomeController extends AbstractController {
		#[Route('/', name: 'app', methods: ['GET'])]
		public function index(): ?Response {
			return $this->redirectToRoute('app_task_private_list_index');
		}
	}

