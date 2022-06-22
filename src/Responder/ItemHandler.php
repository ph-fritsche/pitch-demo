<?php

namespace App\Responder;

use App\Entity\Todo;
use Pitch\AdrBundle\Responder\ResponseHandlerInterface;
use Pitch\AdrBundle\Responder\ResponsePayloadEvent;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ItemHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected ?Environment $twig = null,
    ) {
    }

    public function getSupportedPayloadTypes(): array
    {
        return [
            Todo::class,
        ];
    }

    public function handleResponsePayload(
        ResponsePayloadEvent $payloadEvent
    ): void {
        $payloadEvent->payload = new Response(
            $this->twig->render('todo/show.html.twig', [
                'todo' => $payloadEvent->payload,
            ]),
        );
    }
}
