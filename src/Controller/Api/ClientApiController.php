<?php
namespace App\Controller\Api;

use App\Entity\Client;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/clients")
 * @OA\Tag(name="Clients")
 */
class ClientApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ClientService $service,
        private ValidatorInterface $validator
    ) {}

    /** @Route("", methods={"GET"}) */
    public function list(): JsonResponse
    {
        return $this->json($this->em->getRepository(Client::class)->findAll(), 200, [], ['groups'=>['appointment']]);
    }

    /** @Route("", methods={"POST"}) */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $c = (new Client())
            ->setFirstName($data['firstName'] ?? '')
            ->setLastName($data['lastName'] ?? '')
            ->setEmail($data['email'] ?? '');

        $errors = $this->validator->validate($c);
        if (count($errors)) return $this->json(['error'=>(string)$errors], 400);

        $this->service->upsert($c);
        return $this->json(['message'=>'Created','id'=>$c->getId()], 201);
    }
}
