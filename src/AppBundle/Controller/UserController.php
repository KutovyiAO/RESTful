<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserController extends Controller
{
    /**
     * @Route("/index", name="index")
     * @Method({"GET"})
     */
    public function cgetAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class);
        $allPosts = $user->findAll();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $data = [];

        foreach ($allPosts as $row) {
            $data[] = $serializer->serialize($row, 'json');
        }

        return new JsonResponse($data);

    }
    /**
     * @Route("/create", name="create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $user = new User();

        $user->setName($request->get('name'));
        $user->setAge($request->get('age'));
        $user->setMale($request->get('male'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse($user->getId());
    }
    /**
     * @Route("/get/{id}", name="get")
     * @Method({"GET"})
     */
    public function getAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse($serializer->serialize($user, 'json'));
    }
    /**
     * @Route("/put/{id}", name="put")
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

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse($serializer->serialize($user, 'json'));
    }
    /**
     * @Route("/delete/{id}", name="delete")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        if($user == null) {
            return new JsonResponse(false);
        }
        $em->remove($user);
        $em->flush();

        return new JsonResponse(true);
    }
}
