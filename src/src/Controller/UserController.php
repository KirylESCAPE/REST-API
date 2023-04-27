<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;

class UserController extends ApiController
{
    private const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(private ManagerRegistry $doctrine) {

    }

    public function create(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        UserService $service
    ): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $credentials = $service->get($this->transformJsonBody($request));

        foreach ($credentials as $credential) {
            if (empty($credential)) {
                return $this->respondValidationError("Not All Credentials");
            }
        }

        $user = $service->set($passwordHasher, $credentials);

        try {
            $errors = $validator->validate($user);
            if ($errors->has(0) && $errors->get(0) instanceof ConstraintViolationInterface){
                throw new ValidationException($errors);
            }
        } catch (ValidationException $e) {
            return $this->respondValidationError($e->getMessages());
        }
        $em->persist($user);

        try {
            $em->flush();
        } catch(UniqueConstraintViolationException $e) {
            return new JsonResponse($e->getMessage());
        }

        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }

    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }

    public function update(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        UserService $service,
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorageInterface
    ): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $credentials = $service->get($this->transformJsonBody($request));

        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        $username = $decodedJwtToken['username'];

        if (in_array(self::ROLE_ADMIN, $decodedJwtToken['roles'])) {
            $username = $credentials['username'];
        }
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!$user) {
            throw $this->createNotFoundException('No user found for username => ' . $username);
        }

        $user = $service->update($passwordHasher, $credentials, $user);

        try {
            $errors = $validator->validate($user);
            if ($errors->has(0) && $errors->get(0) instanceof ConstraintViolationInterface){
                throw new ValidationException($errors);
            }
        } catch (ValidationException $e) {
            return $this->respondValidationError($e->getMessages());
        }

        try {
            $em->flush();
        } catch(UniqueConstraintViolationException $e) {
            return new JsonResponse($e->getMessage());
        }

        return $this->respondWithSuccess(sprintf('User %s successfully updated', $user->getUsername()));
    }

    public function delete(Request $request): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $username = $this->transformJsonBody($request)->get('username');
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!$user) {
            throw $this->createNotFoundException('No user found for username => ' . $username);
        }
        $em->remove($user);
        $em->flush();

        return $this->respondWithSuccess(sprintf('User %s successfully deleted', $user->getUsername()));
    }

    public function getAll(UserRepository $userRepository): JsonResponse
    {
        $data = $userRepository->getAll();

        return $this->response($data);
    }
}