<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractController
{

    /**
     * @Route("/verification", name="app_verif")
     */
    public function verif()
    {

        $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $client->select(1);
        $redisIpLogged = $client->exists("logged:".$_SERVER['REMOTE_ADDR']);

        if(!$redisIpLogged) {
            return $this->render('verification/index.html.twig', [
            'controller_name' => 'VerificationController',
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }



    /**
     * @Route("/verif/success", name="verification")
     */
    public function verif_added(Request $request)
    {
        $request = Request::createFromGlobals();

        $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $exist =  $client->exists("logged:".$request->getClientIp());

        if(!$exist) {
            $client->select(1);
            $client->set("logged:".$request->getClientIp(), "identified");
            $client->expire("logged:".$request->getClientIp(), 600);
        }
        return $this->redirectToRoute('app_login');
    }
}
