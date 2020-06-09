<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Games;
use App\Entity\User;
use App\Entity\Category;
use App\Form\EditGameType;
use App\Form\AddGameType;
use App\Form\EditUserAdminType;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $user = $this->getUser();

        $balance = $user->getBalance();


        return $this->render('admin/index.html.twig', [
            'balance' => $balance,
        ]);
    }



    /**
     * @Route("/admin/edit/game/{id}", name="edit_game")
     */
    public function edit_game(int $id, Request $request)
    {

         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $game = $this->getDoctrine()->getRepository(Games::class)->find($id);

        if(!$game)
            return $this->redirectToRoute('admin');


        $form = $this->createForm(EditGameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setGameName($form->get('game_name')->getData());
            $game->setGamePrice($form->get('game_price')->getData());
            $game->setGameDesc($form->get('game_desc')->getData());
            $game->setGameImg($form->get('game_img')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('list_game');
        }

        $user = $this->getUser();
        $balance = $user->getBalance();


        return $this->render('admin/editgame.html.twig', [
            'editgameForm' => $form->createView(),
            'game' => $game,
            'balance' => $balance,
        ]);
    }


    /**
     * @Route("/admin/add/game", name="add_game")
     */
    public function add_game(Request $request)
    {

         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }
        $game = new Games();
        $user = $this->getUser();

        $cat = $this->getDoctrine()->getRepository(Category::class)->find(1);


        $form = $this->createForm(AddGameType::class, $game);
        $form->handleRequest($request);


        $balance = $user->getBalance();

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setGameName($form->get('game_name')->getData());
            $game->setGamePrice($form->get('game_price')->getData());
            $game->setGameDesc($form->get('game_desc')->getData());
            $game->setGameImg($form->get('game_img')->getData());
            $game->setGameNote(0);
            $game->setGameCat($cat);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('list_game');
        }

        return $this->render('admin/addgame.html.twig', [
            'addgameForm' => $form->createView(),
            'balance' => $balance,
        ]);
    }

    /**
     * @Route("/admin/edit/user/{id}", name="edit_user")
     */
    public function edit_user(int $id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $userMe = $this->getUser();
        $balance = $userMe->getBalance(); 

        if(!$user)
            return $this->redirectToRoute('admin');

        $form = $this->createForm(EditUserAdminType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd_encoded = $passwordEncoder->encodePassword($user,  $form->get('password')->getData()); 
            $user->setPassword($new_pwd_encoded);
            $user->setEmail($form->get('email')->getData());
            $user->setLastname($form->get('lastname')->getData());
            $user->setFirstname($form->get('firstname')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('admin/edituser.html.twig', [
            'user' => $user,
            'balance' => $balance,
            'edituserForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/delete/user/{id}", name="del_user")
     */
    public function del_user(int $id, Request $request)
    {

         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user)
            return $this->redirectToRoute('admin');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);      
        $entityManager->flush();


        return $this->redirectToRoute('list_user');
    }


    /**
     * @Route("/admin/delete/game/{id}", name="del_game")
     */
    public function del_game(int $id, Request $request)
    {
         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $game = $this->getDoctrine()->getRepository(Games::class)->find($id);

        if(!$game)
            return $this->redirectToRoute('admin');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($game);      
        $entityManager->flush();


        return $this->redirectToRoute('list_game');
    }


     /**
     * @Route("/admin/game/list", name="list_game")
     */
    public function list_game(Request $request)
    {
         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $repository = $this->getDoctrine()->getRepository(Games::class);
        $games = $repository->findAll();
        $user = $this->getUser();
        $balance = $user->getBalance();

        return $this->render('admin/gamelist.html.twig', [
            'games' => $games,
            'balance' => $balance,
        ]);
    }


    /**
     * @Route("/admin/users/list", name="list_user")
     */
    public function user_list(Request $request)
    {
         $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->redirectToRoute('app_verif');
        } else {
            $client->expire("logged:".$_SERVER['REMOTE_ADDR'], 600);
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        $user = $this->getUser();
        $balance = $user->getBalance();

        return $this->render('admin/userlist.html.twig', [
            'users' => $users,
            'balance' => $balance,
        ]);
    }
}
