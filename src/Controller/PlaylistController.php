<?php

namespace App\Controller;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PlaylistController extends AbstractController
{
    public function playlists(Request $request, SerializerInterface $serializer)
    {
        if ($request->isMethod("GET")) {
            $playlists = $this->getDoctrine()
                ->getRepository(Playlist::class)
                ->findAll();

            $playlists = $serializer->serialize(
                $playlists, 
                'json', 
                ['groups' => ['playlist']]);


            //return new JsonResponse($planets, 200, [], true);
            return new Response($playlists);
        }

        if ($request->isMethod("POST")) {

            $bodyData = $request->getContent();
            $playlist = $serializer->deserialize(
                $bodyData,
                Playlist::class,
                'json'
            );

            $this->getDoctrine()->getManager()->persist($playlist);
            $this->getDoctrine()->getManager()->flush();

            $playlists = $serializer->serialize(
                $playlist, 
                'json', 
                ['groups' => 'playlist']);

                return new Response($playlists);
        }
    }

    public function playlist(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        $playlist = null;

        $playlist = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findOneBy(['id' => $id]);

        if ($request->isMethod("GET")) {

            $playlist = $serializer->serialize(
                $playlist, 
                'json', [
                'groups' => ['playlist']]);

            return new Response($playlist);
        }
    }

    public function playlistsUsuario(Request $request, SerializerInterface $serializer)
    {
        $id = $request->get('id');

        $playlists = null;

        #PLAYLISTS QUE SIGUE
         
        $playlistsSigue = $this->getDoctrine()
        ->getRepository(Usuario::class)
        ->findOneBy(['id' => $id])
        ->getPlaylist();
        

        #PLAYLISTS QUE HA CREADO
        
        $playlistsCrea = $this->getDoctrine()
        ->getRepository(Playlist::class)
        ->findBy(['usuario' => $id]);
        
        #MIX
        $playlists = [$playlistsCrea, $playlistsSigue];

        if ($request->isMethod("GET")) {

            $playlists = $serializer->serialize(
                $playlists, 
                'json', [
                'groups' => ['playlist']]);

            return new Response($playlists);
        }

    }

    public function playlistUsuario(Request $request, SerializerInterface $serializer)
    {
        $usuarioId = $request->get('usuario_id');
        $playlistId = $request->get('playlist_id');

        #FALTA

    }
    
}