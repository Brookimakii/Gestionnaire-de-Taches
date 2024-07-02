<?php

	namespace App\Controller;

	use App\Entity\User;
	use App\Entity\Task;
	use App\Form\UserType;
	use App\Repository\UserRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Attribute\Route;
	use Symfony\Component\Security\Http\Attribute\CurrentUser;

	#[Route('/profile')]
	class UserController extends AbstractController {
//		#[Route('/', name: 'app_profile', methods: ['GET'])]
//		public function index(#[CurrentUser] User $user): Response {
//			return $this->render('user/profile.html.twig', [
//				'user' => $user
//			]);
//		}

//		#[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
//		public function show(User $user): Response {
//			return $this->render('user/show.html.twig', [
//				'user' => $user,
//			]);
//		}
//
//		#[Route('/profile', name: 'app_user_profile', methods: ['GET'])]
//		public function profile(): Response {
//			return $this->render('user/profile.html.twig');
//		}
//
//		#[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
//		public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response {
//			if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
//				$entityManager->remove($user);
//				$entityManager->flush();
//			}
//
//			return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
//		}
	}
