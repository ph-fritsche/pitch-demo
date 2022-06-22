<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\TodoCollection;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Pitch\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo_index', methods: ['GET'])]
    public function index(TodoRepository $todoRepository): TodoCollection
    {
        return new TodoCollection(...$todoRepository->findAll());
    }

    #[Route('/new', name: 'app_todo_new', methods: ['GET', 'POST'])]
    #[Form(TodoType::class, entity: Todo::class)]
    public function new(
        TodoRepository $todoRepository,
        ?Todo $data,
    ): Response {
        $todoRepository->add($data, true);

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_todo_show', methods: ['GET'])]
    public function show(Todo $todo): Todo
    {
        return $todo;
    }

    #[Route('/{id}/edit', name: 'app_todo_edit', methods: ['GET', 'POST'])]
    #[Form(TodoType::class)]
    public function edit(
        TodoRepository $todoRepository,
        Todo $data,
    ): Response {
        $todoRepository->add($data, true);

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
