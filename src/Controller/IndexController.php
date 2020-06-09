<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use App\Entity\Games;
use App\Entity\Buy;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Payplug\Payment;
\Payplug\Payplug::init(array(
  'secretKey' => 'sk_test_2wfGxHfnirX0sBNJx4amvC',
  'apiVersion' => '2019-08-06',
));

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
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
        $tokenInterface = $this->get('security.token_storage')->getToken();
        $isAuthenticated = $tokenInterface->isAuthenticated();

        if ($this->isGranted('ROLE_USER') == true) {
            $user = $this->getUser();
            $email = $user->getEmail();
    
            $firstname = $user->getFirstname();
            $lastname = $user->getLastname();
            $password = $user->getPassword();
            $username = $user->getUsername();
            $balance = $user->getBalance();
        } else {
            $balance = 0;
        }


        $repository = $this->getDoctrine()->getRepository(Games::class);
        $games = $repository->findAll();
        $repository_cat = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository_cat->findAll();

        return $this->render('index/index.html.twig', [
            'games' => $games,
            'balance' => $balance,
            'category' => $category,
        ]);
    }


    /**
     * @Route("/search/{game}", name="game_search")
     */
    public function search(String $game)
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
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();

        $repository = $this->getDoctrine()->getRepository(Games::class);
        $games = $repository->findBy(["game_name" => $game]);
        $repository_cat = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository_cat->findAll();

        return $this->render('index/index.html.twig', [
            'games' => $games,
            'balance' => $balance,
            'category' => $category,
        ]);
    }


    /**
     * @Route("/game/{id}", name="game_view")
     */
    public function view(int $id)
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


        $user = $this->getUser();
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();


        return $this->render('index/product.html.twig', [
            'game' => $game,
            'balance' => $balance,
        ]);
    }


     /**
    * @Route("/game/paiement/{id}", name="paiement")
    */
    public function paiement($id)
    {
      $user = $this->getUser();
      $game = $this->getDoctrine()->getRepository(Games::class)->find($id);

      $amount = $game->getGameprice();
      $customer_id = $user->getId();

      $payment = \Payplug\Payment::create(array(
        'amount'           => $amount * 100,
        'currency'         => 'EUR',
        'billing'  => array(
          'title'        => 'Mr',
          'first_name'   => $user->getFirstname(),
          'last_name'    => $user->getLastname(),
          'email'        => $user->getEmail(),
          'address1'     => '5 impasse des pommiers',
          'mobile_phone_number'  => '+33605312711',
          'postcode'     => '77860',
          'city'         => 'Saint-Germain-Sur-Morin',
          'country'      => 'FR',
          'language'     => 'fr'
        ),
        'shipping'  => array(
          'title'        => 'Mr',
          'first_name'   => $user->getFirstname(),
          'last_name'    => $user->getLastname(),
          'email'        => $user->getEmail(),
          'address1'     => '5 impasse des pommiers',
          'mobile_phone_number'  => '+33605312711',
          'postcode'     => '77860',
          'city'         => 'Saint-Germain-Sur-Morin',
          'country'      => 'FR',
          'language'     => 'fr',
          'delivery_type' => 'BILLING'
        ),
        'hosted_payment'   => array(
          'return_url'     => 'http://127.0.0.1:8000/game/paiement/success/'.$id,
          'cancel_url'     => 'http://127.0.0.1:8000/profil',
          'sent_by'        => 'SMS'
        ),
        'notification_url' => 'http://127.0.0.1:8000/profil',
          'metadata'         => array(
            'customer_id'    => $customer_id
          )
        ));

        $payment_url = $payment->hosted_payment->payment_url;
        $payment_id = $payment->id;
        header('Location:' . $payment_url);
        exit();
      }


      function generate_license($suffix = null) {
        // Default tokens contain no "ambiguous" characters: 1,i,0,o
        if(isset($suffix)){
            // Fewer segments if appending suffix
            $num_segments = 3;
            $segment_chars = 6;
        }else{
            $num_segments = 4;
            $segment_chars = 5;
        }
        $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $license_string = '';
        // Build Default License String
        for ($i = 0; $i < $num_segments; $i++) {
            $segment = '';
            for ($j = 0; $j < $segment_chars; $j++) {
                $segment .= $tokens[rand(0, strlen($tokens)-1)];
            }
            $license_string .= $segment;
            if ($i < ($num_segments - 1)) {
                $license_string .= '-';
            }
        }
        // If provided, convert Suffix
        if(isset($suffix)){
            if(is_numeric($suffix)) {   // Userid provided
                $license_string .= '-'.strtoupper(base_convert($suffix,10,36));
            }else{
                $long = sprintf("%u\n", ip2long($suffix),true);
                if($suffix === long2ip($long) ) {
                    $license_string .= '-'.strtoupper(base_convert($long,10,36));
                }else{
                    $license_string .= '-'.strtoupper(str_ireplace(' ','-',$suffix));
                }
            }
        }
        return $license_string;
    }


       /**
    * @Route("/game/paiement/success/{id}/", name="paiement_success")
    */
    public function paiement_success(int $id)
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

        $user = $this->getUser();
        $email = $user->getEmail();

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $password = $user->getPassword();
        $username = $user->getUsername();
        $balance = $user->getBalance();


            $buy = new Buy();

            $buy->setDate(new \DateTime('@'.strtotime('now')));
            $buy->setGameid($game);
            $buy->setPayid("pay_id");
            $buy->addUserid($user);
            $buy->setGamekey($this->generate_license());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($buy);
            $entityManager->flush();

            return new jsonresponse(array('success' => $this->generate_license()));


    }
}
