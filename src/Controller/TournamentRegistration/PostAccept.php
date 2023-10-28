<?php

declare(strict_types=1);

namespace App\Controller\TournamentRegistration;

use App\Exception\Conflict;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use App\Entity\TournamentRegistration;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\TournamentRegistrationOutput;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Event\AcceptedTournamentRegistrationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ObjectManipulation\TransferTournamentRegistrationToOutput;

class PostAccept extends AbstractController
{
    #[Route('/api/tournament-registrations/{uuid}/accept', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Accept a tournament registration',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentRegistrationOutput::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Tournament registration or user not found'
    )]
    #[OA\Response(
        response: 409,
        description: 'Tournament registration has been accepted or refused'
    )]
    #[OA\Tag(name: 'TournamentRegistration')]
    public function __invoke(
        string $uuid,
        EntityManagerInterface $entityManager,
        TournamentRegistrationOutput $tournamentRegistrationOutput,
        TransferTournamentRegistrationToOutput $transferTournamentRegistrationOutput,
        EventDispatcherInterface $dispatcher,
    ): Response {
        $tournamentRegistration = $entityManager->getRepository(TournamentRegistration::class)->find($uuid);

        if ($tournamentRegistration === null) {
            throw new NotFound('Tournament registration not found');
        }

        if ($tournamentRegistration->getStatus() !== 'pending') {
            if ($tournamentRegistration->getStatus() === 'accepted') {
                throw new Conflict('Tournament registration has been accepted');
            } else {
                throw new Conflict('Tournament registration has been refused');
            }
        }

        if (in_array('ROLE_ADMIN', $tournamentRegistration->getUser()->getRoles(), true) === false) {
            $tournamentRegistration->getUser()->setRole('ROLE_PLAYER');
        }

        $tournamentRegistration->setStatus('accepted');

        $entityManager->flush();

        $event = new AcceptedTournamentRegistrationEvent($tournamentRegistration);
        $dispatcher->dispatch($event, AcceptedTournamentRegistrationEvent::NAME);

        $transferTournamentRegistrationOutput($tournamentRegistration, $tournamentRegistrationOutput);

        return $this->json($tournamentRegistrationOutput, 201);
    }
}