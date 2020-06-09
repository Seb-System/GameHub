<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ChangpassFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Cache\Adapter\RedisAdapter;





class UserController extends AbstractController
{

    /**
     * @Route("/profil/edit", name="user_edit")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    
        $user = $this->getUser();
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();

        $form = $this->createForm(ChangpassFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pass = $form->get('password')->getData();
            $new_pwd = $form->get('confirm_password')->getData(); 
            $checkPass = $passwordEncoder->isPasswordValid($user, $old_pass);

            if($checkPass === true) {
                $new_pwd_encoded = $passwordEncoder->encodePassword($user, $new_pwd); 
                $user->setPassword($new_pwd_encoded);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                return new jsonresponse(array('error' => 'The current password is incorrect.'));
            }
        }

        return $this->render('user/editprofil.html.twig', [
            'email' => $email,
            'balance' => $balance,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'username' => $username,
            'changpassForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/profil", name="profil")
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    
        $user = $this->getUser();
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();

        $form = $this->createForm(ChangpassFormType::class, $user);
        $form->handleRequest($request);


        return $this->render('user/profil.html.twig', [
            'email' => $email,
            'balance' => $balance,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'username' => $username,
            'password' => $password,
        ]);
    }

    /**
     * @Route("/profil/solde", name="solde")
     */
    public function solde(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    
        $user = $this->getUser();
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();

        return $this->render('user/solde.html.twig', [
            'email' => $email,
            'balance' => $balance,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'username' => $username,
            'password' => $password,
        ]);
    }

}
