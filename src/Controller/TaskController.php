<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{
    /**
     * @return [type]
     *
     * @Route("/tasks", name="task_list")
     */
    public function listTask(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();

        return $this->render(
            'task/list.html.twig',
            ['tasks' => $tasks]
        );
    }

    /**
     * @return [type]
     *
     * @Route("/tasks/done", name="task_list_done", methods={"GET"})
     */
    public function tasksDone(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findBy(['isDone' => 'True']);

        return $this->render(
            'task/list_done.html.twig',
            ['tasks' => $tasks]
        );
    }

    /**
     * @return [type]
     *
     * @Route("/tasks/create", name="task_create")
     */
    public function createTask(Request $request, Security $security)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($security->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param mixed $id
     *
     * @return [type]
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editTask($id, Request $request, TaskRepository $taskRepository)
    {
        $task = $taskRepository->find($id);
        if (!($task)) {
            $this->addFlash('error', "Cette tâche n'existe pas");

            return $this->RedirectToRoute('task_list');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @return [type]
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();
        if ($task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s reste à faire.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @param mixed $id
     *
     * @return [type]
     *
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTask($id, TaskRepository $taskRepository)
    {
        $task = $taskRepository->find($id);
        if (!($task)) {
            $this->addFlash('error', "Cette tâche n'existe pas");

            return $this->RedirectToRoute('task_list');
        }
        $this->denyAccessUnlessGranted('TASK_DELETE', $task, "Vous n'etes pas l'auteur de cette tâche");
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
