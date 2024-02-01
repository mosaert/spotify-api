<?php

namespace App\Controller;

use App\Entity\Capitulo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CapituloController extends AbstractController {
    public function capitulosPodcast(Request $request, SerializerInterface $serializer){
        $id= $request->get('id');
        $capitulos= $this->getDoctrine()->getRepository(Capitulo::class)->findBy(['podcast' => $id]);
        if ($request->isMethod('GET')){
            $capitulos= $serializer->serialize($capitulos, 'json', ['groups'=>['capitulo']]);
            return new Response($capitulos);
        }
    }
    public function capitulo(Request $request, SerializerInterface $serializer){
        $id_podcast = $request->get('podcast_id');
        $id_capitulo = $request->get('capitulo_id');

        if (!$id_podcast || !$id_capitulo) {
            return new JsonResponse(['msg' => 'parametros pochos'], 400);
        }
        $capitulo = $this->getDoctrine()->getRepository(Capitulo::class)->find($id_capitulo);

        if ($capitulo && $capitulo->getPodcast() && $capitulo->getPodcast()->getId() == $id_podcast) {
            $capitulo = $serializer->serialize($capitulo, 'json', ['groups' => ['capitulo']]);
            return new Response($capitulo);
        }
        else {
            return new JsonResponse(['msg' => 'no hay caps'], 404);
        }
    }
}