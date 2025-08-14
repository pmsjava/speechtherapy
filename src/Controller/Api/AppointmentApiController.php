<?php
namespace App\Controller\Api;

use App\Entity\Appointment;
use App\Service\AppointmentService;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/appointments")
 * @OA\Tag(name="Appointments")
 */
class AppointmentApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private AppointmentService $service,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {}

    /** @Route("", methods={"GET"}) @OA\Get(path="/api/appointments", security={{"Bearer":{}}}) */
    public function list(Request $request): JsonResponse
    {
        // Prosty rate limit (global limiter z configu)
        $limiter = $this->container->get('limiter.api_global');
        $limit = $limiter->consume(1);
        if (!$limit->isAccepted()) {
            return new JsonResponse(['error'=>'Too Many Requests'], 429, ['Retry-After' => max(0, $limit->getRetryAfter()->getTimestamp() - time())]);
        }

        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = min(100, max(1, (int)$request->query->get('perPage', 10)));
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $q = trim((string)$request->query->get('q', ''));

        $qb = $this->em->getRepository(Appointment::class)->createQueryBuilder('a')
            ->join('a.client','c');

        if ($from) { $qb->andWhere('a.appointmentDate >= :from')->setParameter('from', new \DateTime($from)); }
        if ($to)   { $qb->andWhere('a.appointmentDate <= :to')->setParameter('to', new \DateTime($to)); }
        if ($q)    { $qb->andWhere('c.firstName LIKE :q OR c.lastName LIKE :q OR c.email LIKE :q')->setParameter('q','%'.$q.'%'); }

        $qb->orderBy('a.appointmentDate','ASC');

        $adapter = new QueryAdapter($qb->getQuery());
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($perPage)->setCurrentPage($page);

        $items = iterator_to_array($pager->getCurrentPageResults());
        $json = $this->serializer->serialize($items, 'json', ['groups'=>['appointment']]);

        return new JsonResponse([
            'page' => $page,
            'perPage' => $perPage,
            'total' => $pager->getNbResults(),
            'items' => json_decode($json, true)
        ]);
    }

    /** @Route("/{id}", methods={"GET"}) @OA\Get(path="/api/appointments/{id}", security={{"Bearer":{}}}) */
    public function getOne(int $id): JsonResponse
    {
        $a = $this->em->getRepository(Appointment::class)->find($id);
        if (!$a) return new JsonResponse(['error'=>'Not found'], 404);
        return new JsonResponse($this->serializer->serialize($a, 'json', ['groups'=>['appointment']]), 200, [], true);
    }

    /** @Route("", methods={"POST"}) @OA\Post(path="/api/appointments", security={{"Bearer":{}}}) */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $a = new Appointment();

        $c = new \App\Entity\Client();
        $c->setFirstName($data['client']['firstName'] ?? '')
          ->setLastName($data['client']['lastName'] ?? '')
          ->setEmail($data['client']['email'] ?? '');
        $a->setClient($c);

        try {
            $a->setAppointmentDate(new \DateTime($data['appointmentDate'] ?? 'now'));
        } catch (\Exception $e) {
            return new JsonResponse(['error'=>'Invalid date'], 400);
        }

        $errors = $this->validator->validate($a);
        if (count($errors)) return new JsonResponse(['error' => (string)$errors], 400);

        $this->service->save($a);
        return new JsonResponse(['message'=>'Created','id'=>$a->getId()], 201);
    }

    /** @Route("/{id}", methods={"PUT"}) @OA\Put(path="/api/appointments/{id}", security={{"Bearer":{}}}) */
    public function update(int $id, Request $request): JsonResponse
    {
        $a = $this->em->getRepository(Appointment::class)->find($id);
        if (!$a) return new JsonResponse(['error'=>'Not found'], 404);

        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['client'])) {
            $c = $a->getClient();
            $c->setFirstName($data['client']['firstName'] ?? $c->getFirstName());
            $c->setLastName($data['client']['lastName'] ?? $c->getLastName());
            $c->setEmail($data['client']['email'] ?? $c->getEmail());
        }

        if (isset($data['appointmentDate'])) {
            try { $a->setAppointmentDate(new \DateTime($data['appointmentDate'])); }
            catch (\Exception) { return new JsonResponse(['error'=>'Invalid date'], 400); }
        }

        $errors = $this->validator->validate($a);
        if (count($errors)) return new JsonResponse(['error'=>(string)$errors], 400);

        $this->service->save($a);
        return new JsonResponse(['message'=>'Updated']);
    }

    /** @Route("/{id}", methods={"DELETE"}) @OA\Delete(path="/api/appointments/{id}", security={{"Bearer":{}}}) */
    public function delete(int $id): JsonResponse
    {
        $a = $this->em->getRepository(Appointment::class)->find($id);
        if (!$a) return new JsonResponse(['error'=>'Not found'], 404);
        $this->service->delete($a);
        return new JsonResponse(['message'=>'Deleted']);
    }
}
