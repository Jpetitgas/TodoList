<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    
    
    /**
     * @Route("/users", name="user_list")
     */
    public function listUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render(
            'user/list.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editUser($id,Request $request, UserPasswordEncoderInterface $encoder,UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        if (!($user)) {
            $this->addFlash('error', "L'utilisateur n'existe pas");
            return $this->RedirectToRoute('user_list');
        }
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    /**
     * @Route("/users/{id}/delete", name="user_delete")
     */
    public function deleteUser($id, Request $request,UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->find($id);
        if (!($user)) {
            $this->addFlash('error', "L'utilisateur n'existe pas");
            return $this->RedirectToRoute('user_list');
        }
       
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', "L'utilisateur a bien été supprimé");
        return $this->RedirectToRoute('user_list');
    }
}
