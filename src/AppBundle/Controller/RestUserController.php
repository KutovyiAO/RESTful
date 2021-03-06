<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class RestUserController
 * @package AppBundle\Controller
 */
class RestUserController extends FOSRestController
{
    /**
     * @param Request $request
     * @Route("/home", name="home")
     * @Method({"GET"})
     * @return View
     */
    public function ccgetAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        $view = $this->view($user);

        return $this->handleView($view);

    }

    /**
     * @param Request $request
     * @return View
     * @Route("/createpage", name="newcreate")
     * @Method({"POST"})
     */
    public function createAction(Request $request){

        $user = new User();

        $user->setName($request->get('name'));
        $user->setAge($request->get('age'));
        $user->setMale($request->get('male'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view($user);
    }

    /**
     * @param Request $request
     * @return View
     * @Route("/putpage/{id}", name="putpage")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        $user->setName($request->get('name'));
        $user->setAge($request->get('age'));
        $user->setMale($request->get('male'));

        $em->persist($user);
        $em->flush();

        return $this->view($user);
    }

    /**
     * @param $id
     * @Method({"DELETE"})
     * @Route("/deleterest/{id}", name="deleterest")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();

        return $this->view($user);

    }
}