<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

class UsuarioController extends AbstractController
{
    public function usuarios(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){
        if($request->isMethod('GET')){
            $usuarios= $this->getDoctrine()->getRepository(Usuario::class)->findAll();
            $usuarios= $serializer->serialize($usuarios,'json', ['groups'=>'usuario']);

            return new Response($usuarios);
        }
        if ($request->isMethod('POST')){
            // crea usuario, pero no le asigna free o premium ni configuracion :(
            $entityManager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);
            $usuario = new Usuario();
            if (isset($data['username'])) {
                $usuario->setUsername($data['username']);
            }
            if (isset($data['password'])) {
                $usuario->setPassword($data['password']);
            }
            if (isset($data['email'])) {
                $usuario->setEmail($data['email']);
            }
            if (isset($data['genero'])) {
                $usuario->setGenero($data['genero']);
            }
            if (isset($data['fecha_nacimiento'])) {
                $usuario->setFechaNacimiento(new \DateTime($data['fecha_nacimiento']));
            }
            if (isset($data['pais'])) {
                $usuario->setPais($data['pais']);
            }
            if (isset($data['codigo_postal'])) {
                $usuario->setCodigoPostal($data['codigo_postal']);
            }
            $entityManager->persist($usuario);
            $entityManager->flush();

            return new Response('Usuario creado correctamente', Response::HTTP_CREATED);
        }
    }
    public function usuario(Request $request, SerializerInterface $serializer){
        $id= $request->get('id');
        $usuario= $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);

        if($request->isMethod('GET')){
            $usuario= $serializer->serialize($usuario, 'json', ['groups'=>'usuario']);
            return new Response($usuario);
        }
        if ($request->isMethod('PUT')){
            $entityManager = $this->getDoctrine()->getManager();
            $usuario = $entityManager->getRepository(Usuario::class)->find($id);

            if (!$usuario) {
                return new Response('usuario no encontrado', Response::HTTP_NOT_FOUND);
            }
            $data = json_decode($request->getContent(), true);

            if (isset($data['username'])) {
                $usuario->setUsername($data['username']);
            }
            if (isset($data['password'])) {
                $usuario->setPassword($data['password']);
            }
            if (isset($data['email'])) {
                $usuario->setEmail($data['email']);
            }
            if (isset($data['genero'])) {
                $usuario->setGenero($data['genero']);
            }
            if (isset($data['fecha_nacimiento'])) {
                $usuario->setFechaNacimiento(new \DateTime($data['fecha_nacimiento']));
            }
            if (isset($data['pais'])) {
                $usuario->setPais($data['pais']);
            }
            if (isset($data['codigo_postal'])) {
                $usuario->setCodigoPostal($data['codigo_postal']);
            }
            $entityManager->flush();
            return new Response('usuario actualizado correctamente', Response::HTTP_OK);
        }
        if ($request->isMethod('DELETE')){
            $deletedUser = clone $usuario;
            $this->getDoctrine()->getManager()->remove($usuario);
            $this->getDoctrine()->getManager()->flush();
            $deletedUser= $serializer->serialize($deletedUser, 'json', ['groups'=>['usuario']]);
            return new Response($deletedUser);
        }
    }
}