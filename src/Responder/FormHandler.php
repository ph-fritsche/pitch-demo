<?php

namespace App\Responder;

use App\Entity\Todo;
use Pitch\AdrBundle\Responder\ResponseHandlerInterface;
use Pitch\AdrBundle\Responder\ResponsePayloadEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class FormHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected ?Environment $twig = null,
    ) {
    }

    public function getSupportedPayloadTypes(): array
    {
        return [
            FormInterface::class,
        ];
    }

    public function handleResponsePayload(
        ResponsePayloadEvent $payloadEvent
    ): void {
        /** @var FormInterface */
        $form = $payloadEvent->payload;
        /** @var Todo */
        $todo = $form->getData();

        $payloadEvent->payload = new Response(
            $this->twig->render(
                $todo->getId() ? 'todo/edit.html.twig' : 'todo/new.html.twig',
                [
                    'todo' => $todo,
                    'form' => $form->createView(),
                ],
            ),
        );
    }
}
