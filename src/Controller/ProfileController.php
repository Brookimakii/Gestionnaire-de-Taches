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

	#[Route('profile')]
	class ProfileController extends AbstractController {
		#[Route('/', name: 'app_profile', methods: ['GET'])]
		public function profile(#[CurrentUser] User $user): Response {
			return $this->render('user/profile.html.twig', [
				'user' => $user
			]);
		}

		#[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
		public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response {
			$form = $this->createForm(UserType::class, $user);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->flush();

				return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
			}

			return $this->render('user/edit.html.twig', [
				'user' => $user,
				'form' => $form,
			]);
		}

	}