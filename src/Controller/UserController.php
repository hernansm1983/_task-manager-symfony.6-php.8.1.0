<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{    
    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        
        //---CREAR EL FORMULARIO---
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        
        //--- VINCULAMOS EL FORMULARIO CON EL OBJETO ---
        //--- rellenamos el objeto con los datos del form ---
        $form->handleRequest($request);
        
        //---COMRPOBAR SI EL FORM SE HA ENVIADO---
        if($form->isSubmitted() && $form->isValid()){
            
            //--- MODIFICAMOS EL OBJETO PARA GUARDARLO---
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));
            //$date_now = (new \DateTime())->format('Y-m-d H:i:s');
            //var_dump($date_now);
            //die;
           // $user->setCreatedAt($date_now);
            
            //--- CIFRAMOS LA CONTRASENA ---
            $encoded = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoded);
            
            //var_dump($user);
            //--- GUARDAR USUARIO ---
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            //var_dump($user);
            return $this->redirectToRoute('tasks');
        }
        
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('user/login.html.twig', array(
                'error' => $error,
                'last_username' => $lastUsername
        ));
        
    }
}
