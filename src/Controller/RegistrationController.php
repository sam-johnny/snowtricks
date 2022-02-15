<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        SendEmail                   $sendEmail,
        TokenGeneratorInterface     $tokenGenerator
    ): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationToken = $tokenGenerator->generateToken();
            $user->setRegistrationToken($registrationToken)
                ->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));

            $this->entityManager->persist($user);

            $this->entityManager->flush();

            $sendEmail->send([
                'recipient_email' => $user->getEmail(),
                'subject' => "Vérification de votre adresse e-mail pour activer votre compte utilisateur",
                'html_template' => "registration/register_confirmation_email.html.twig",
                'context' => [
                    'userID' => $user->getId(),
                    'registrationToken' => $registrationToken,
                    'tokenLifeTime' => $user->getAccountMustBeVerifiedBefore()->format('d/m/Y à H:i')
                ]
            ]);

            $this->addFlash('success', "Votre compte utilisateur à bien été créé, vérifiez vos e-mail pour l'activer.");

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/{token}', name: 'app_verify_account', methods: ['GET'])]
    public function verifyAccount(
        User   $user,
        string $token,
    ): Response
    {
        if ($user->getRegistrationToken() === null || ($user->getRegistrationToken()) !== $token || ($this->isNotRequestedInTime($user->getAccountMustBeVerifiedBefore()))) {
            throw new AccessDeniedException();
        }

        $user->setIsVerified(true)
            ->setAccountVerifiedAt(new \DateTimeImmutable('now'))
            ->setRegistrationToken(null);

        $this->entityManager->flush();

        $this->addFlash('success', 'Votre compte utilisateur est activé, vous pouvez vous connecter !');

        return $this->redirectToRoute('login');

    }

    private function isNotRequestedInTime(\DateTime $accountMustBeVerifiedBefore): bool
    {
        return (new \DateTimeImmutable('now') > $accountMustBeVerifiedBefore);
    }
}




