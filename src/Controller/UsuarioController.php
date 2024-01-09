<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UsuarioController extends AbstractController
{
    public function usuarios(Request $request, SerializerInterface $serializer){
        if($request->isMethod('GET')){
            $usuarios= $this->getDoctrine()->getRepository(Usuario::class)->findAll();
            $usuarios= $serializer->serialize($usuarios,'json', ['groups'=>'usuario']);

            return new Response($usuarios);
        }
    }
    public function usuario(Request $request, SerializerInterface $serializer){
        $id= $request->get('id');
        $usuario= $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id]);
        if($request->isMethod('GET')){
            $usuario= $serializer->serialize($usuario, 'json', ['groups'=>'usuario']);
            return new Response($usuario);
        }
        if ($request->isMethod('PUt')){

        }
    }
}