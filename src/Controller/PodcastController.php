<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PodcastController extends AbstractController {
    public function podcasts(Request $request, SerializerInterface $serializer){
        if($request->isMethod("GET")){
            $podcasts= $this->getDoctrine()->getRepository(Podcast::class)->findAll();
            $podcasts= $serializer->serialize($podcasts, 'json', ['groups'=>['podcast']]);

            return new Response($podcasts);
        }
    }
    public function podcast(Request $request, SerializerInterface $serializer){
        if ($request->isMethod('GET')){
            $id= $request->get('id');
            $podcast= $this->getDoctrine()->getRepository(Podcast::class)->findOneBy(['id'=>$id]);
            $podcast= $serializer->serialize($podcast, 'json', ['groups'=>'podcast']);

            return new Response($podcast);
        }
    }
    public function podcastsUsuario(Request $request, SerializerInterface $serializer){
        if ($request->isMethod('GET')){
            $id= $request->get('id');
            $usuario= $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
            $podcasts= $usuario->getPodcast();
            $podcastsArray= [];
            foreach ($podcasts as $podcast){
                $podcastsArray[] = [
                    'id' => $podcast->getId(),
                    'titulo'=> $podcast->getTitulo(),
                    'descripcion'=> $podcast->getDescripcion(),
                    'titulo'=> $podcast->getTitulo(),
                    'anyo'=> $podcast->getAnyo()
                ];
            }
            $podcastList= $serializer->serialize($podcastsArray, 'json');
            return new Response($podcastList);
        }
    }
    public function usuarioSiguePodcast(Request $request, SerializerInterface $serializer){
        $id_usuario = $request->get('usuario_id');
        $id_podcast = $request->get('podcast_id');

        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=> $id_usuario]);
        $podcast = $this->getDoctrine()->getRepository(Podcast::class)->findOneBy(['id'=> $id_podcast]);

        if ($request->isMethod('POST')) {
            if (!$usuario->getPodcast()->contains($podcast)) {
                $podcasts = $usuario->getPodcast();
                $podcasts[] = $podcast;
                $usuario->setPodcast($podcasts);

                $this->getDoctrine()->getManager()->persist($usuario);
                $this->getDoctrine()->getManager()->flush();
                $usuario = $serializer->serialize($usuario, 'json', ['groups'=> ['usuario']]);

                return new Response($usuario);
            }
        }
        if ($request->isMethod('DELETE')) {
            if ($usuario->getPodcast()->contains($podcast)) {
                $podcasts = $usuario->getPodcast();
                $podcasts->removeElement($podcast);
                $usuario->setPodcast($podcasts);

                $this->getDoctrine()->getManager()->persist($usuario);
                $this->getDoctrine()->getManager()->flush();
                $usuario = $serializer->serialize($usuario, 'json', ['groups'=> ['usuario']]);

                return new Response($usuario);
            }
        }
    }
}