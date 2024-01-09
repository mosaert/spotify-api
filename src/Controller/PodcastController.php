<?php

namespace App\Controller;

use App\Entity\Podcast;
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
    public function podcastsUsuario(){
    }
    public function podcastEdit(){

    }
}