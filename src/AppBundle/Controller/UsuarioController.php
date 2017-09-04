<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Usuario;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends FOSRestController
{
    /**
     * @Rest\Get("/users")
     */
    public function getAction()
    {
        $restResult = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll();
        if($restResult === null) {
            return new View("there's no users", Response::HTTP_NOT_FOUND);
        }
        return $restResult;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function getOneAction(Request $request)
    {
        $id = $request->get('id');
        $restResult = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
        if($restResult === null) {
            return new View("there's no users with id " + $id, Response::HTTP_NOT_FOUND);
        }
        return $restResult;
    }


    /**
     * @Rest\Post("/user")
     */
    public function postAction(Request $request)
    {
        $data = new Usuario();
        $nombre = $request->get('nombre');
        $rol = $request->get('rol');

        if(empty($nombre) || empty($rol))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        $data->setName($nombre);
        $data->setRole($rol);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Usuario creado", Response::HTTP_OK);
    }
}
