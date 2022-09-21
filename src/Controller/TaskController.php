<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Persistence\ManagerRegistry;

class TaskController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        //---prueba de entidades y relaciones Task---
        $task_repo = $doctrine->getRepository(Task::class);
        $tasks = $task_repo->findBy([], ['id' => 'DESC']); //--- Trae todas las tareas ordenadas de forma Descendente
        /*
        foreach($tasks as $task){
            echo $task->getTitle()." - ".$task->getUser()->getEmail()."</br>";
        }*/
        
        //---prueba de entidades y relaciones User---
        //$user_repo = $doctrine->getRepository(User::class);
        //$users = $user_repo->findAll();
        /*
        foreach($users as $user){
            echo "<h1>".$user->getName().', '.$user->getSurname()."</h1></br>";
            
            foreach($user->getTasks() as $task){
                echo $task->getTitle()."</br>";
            
            }
        }*/
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }
    
    public function detail(Task $task){
        if(!$task){
            return $this->redirectToRoute('tasks');
        }
        
        return $this->render('task/detail.html.twig',
                ['task' => $task]);
    }
    
    public function creation(ManagerRegistry $doctrine, Request $request, UserInterface $user):Response 
    {
        //---CREAR EL FORMULARIO---
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        
        //--- VINCULAMOS EL FORMULARIO CON EL OBJETO ---
        //--- rellenamos el objeto con los datos del form ---
        $form->handleRequest($request);
        
        //---COMRPOBAR SI EL FORM SE HA ENVIADO---
        if($form->isSubmitted() && $form->isValid()){
           // var_dump($task);
            
            //--- MODIFICAMOS EL OBJETO PARA GUARDARLO---
            //$task->setRole('ROLE_USER');
            $task->setUser($user);
            $task->setCreatedAt(new \DateTime('now'));
                       
            //--- GUARDAR USUARIO ---
            $em = $doctrine->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }
        
        return $this->render('task/creation.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    
    public function myTasks(UserInterface $user){
        $tasks = $user->getTasks();
        
        return $this->render('task/my-tasks.html.twig',
                ['tasks' => $tasks]);   
    }
    
    
    public function edit(Request $request, UserInterface $user, Task $task, ManagerRegistry $doctrine){
        
        //---SOLO DEJA EDITAR AL USUARIO QUE CREO LA TAREA---
        if(!$user || $user->getId() != $task->getUser()->getId()){
            return $this->redirectToRoute('tasks');
        }
        //---CREAR EL FORMULARIO---
        $form = $this->createForm(TaskType::class, $task);
        
        //--- VINCULAMOS EL FORMULARIO CON EL OBJETO ---
        //--- rellenamos el objeto con los datos del form ---
        $form->handleRequest($request);
        
        //---COMRPOBAR SI EL FORM SE HA ENVIADO---
        if($form->isSubmitted() && $form->isValid()){
           // var_dump($task);
            
            //--- MODIFICAMOS EL OBJETO PARA GUARDARLO---
            //$task->setRole('ROLE_USER');
            $task->setUser($user);
            $task->setCreatedAt(new \DateTime('now'));
                       
            //--- GUARDAR USUARIO ---
            $em = $doctrine->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }    
        return $this->render('task/creation.html.twig',
                ['edit'=>true,
                 'form'=>$form->createView()]);
    }
    
    public function delete(Task $task, ManagerRegistry $doctrine, UserInterface $user){
        
        //---SOLO DEJA EDITAR AL USUARIO QUE CREO LA TAREA---
        if(!$user || $user->getId() != $task->getUser()->getId()){
            return $this->redirectToRoute('tasks');
        }
        
        //---SI NO EXISTE LA TAREA, REDIRECCIONA---
        if(!$task){
            return $this->redirectToRoute('tasks');
        }
        
        $em = $doctrine->getManager();
        $em->remove($task);
        $em->flush();
        
        return $this->redirectToRoute('tasks');
    }
}
