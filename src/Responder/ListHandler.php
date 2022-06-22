<?php

namespace App\Responder;

use App\Entity\TodoCollection;
use Pitch\AdrBundle\Responder\ResponseHandlerInterface;
use Pitch\AdrBundle\Responder\ResponsePayloadEvent;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected ?Environment $twig = null,
    ) {
    }

    public function getSupportedPayloadTypes(): array
    {
        return [
            TodoCollection::class,
        ];
    }

    public function handleResponsePayload(
        ResponsePayloadEvent $payloadEvent
    ): void {
        $payloadEvent->payload = new Response(
            $this->twig->render('todo/index.html.twig', [
                'todos' => $payloadEvent->payload,
            ])
        );
    }
}
