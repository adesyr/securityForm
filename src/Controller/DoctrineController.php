<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class DoctrineController extends AbstractController
{
    /**
     * @Route("/doctrine", name="doctrine")
     */
    public function index()
    {

        $user = new User();
        $user -> setName();
        $user -> setEmail();
        $user -> setBirthday();
        $user -> setIsConnected();
        $user -> setCreatedAt(new \DateTime());
        $user -> setPoints();

        return $this->render('doctrine/login.html.twig', ['user' => $user]);
    }


    /**
     * @Route("/doctrine/create", name="createUser")
     */
    public function create()
    {
        $user = new User();
        $user -> setName("toto");
        $user -> setEmail("toto@mail.comm");
        $user -> setBirthday(new \DateTime('2019/12/11'));
        $user -> setIsConnected(false);
        $user -> setCreatedAt(new \DateTime());
        $user -> setPoints(10);
        //Appel au manager
        $em = $this ->getDoctrine()->getManager();
        //Gestion de l'entité
        $em->persist($user);

        //Validation de l'entrée
        $em->flush();


       // return new Response('Utilisateur enregistré =D');
        return $this->render('exo/login.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/doctrine/update{id}", name="doctrine/updateUser")
     */
    public function update($id)
    {
        $em = $this ->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);

        if( $user == null) {
            throw new NotFoundHttpException();
        }

        $user->setName("tutu");
        $user->setEmail("tutu@mail.com");
        $user->setBirthday(new \DateTime('2012/01/01'));
        $user->setIsConnected(false);
        $user->setPoints(25);

        $em ->flush();

        return new Response("Utilisateur modifié ;)");
    }

    /**
     * @Route("/doctrine/read{id}", name="doctrine/readUser")
     */
    public function read($id)
    {
        $em = $this ->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);

        //Générer une 404 si l'id ne correspond pas
        if ($user == null) {
            throw new NotFoundHttpException();
        }
        return $this->render('exo/login.html.twig', ['user' =>$user]);
    }

    /**
     * @Route("/doctrine/delete/{id}", name="doctrine/deleteUser")
     */
    public function delete($id)
    {
        $em = $this ->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);


        //Générer une 404 si l'id ne correspond pas
        if ($user == null) {
            throw new NotFoundHttpException();
        }

        $em ->remove($user);
        $em->flush();

        return new Response('Utilisateur supprimé XD');
    }

    /**
     * @Route("/doctrine-advaned-query", name="doctrine_advanced_query")
     */
    public function advancedQuery() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:User');

        $qb = $repo->createQueryBuilder('u');
        $qb->select('u')
            ->where('u.name LIKE name')
            ->orWhere("u.email = :email")
            ->setParameter('name', 't%')
            ->setParameter('email', 'blabla@mail.fr')
        ;
        $query = $qb->getQuery();
        $users = $query->getResult();
        //Pour un seul resultat  $query->getSingleResult();
        //Pour une seule colonne a retourner  $query->getSingleScalarResult();

        $nbUsers = $repo->countUsers();
        return new Response();
    }
}
