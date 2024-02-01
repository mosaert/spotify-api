<?php

namespace App\Controller;

use App\Entity\Configuracion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{
    public function configuracion(Request $request, SerializerInterface $serializer){
        $id = $request->get('usuario_id');
        $configuracion = $this->getDoctrine()->getRepository(Configuracion::class)->findOneBy(['usuario' => $id]);
        if (!empty($configuracion)) {
            if ($request->isMethod("GET")) {
                $configuracion = $serializer->serialize($configuracion, 'json', ['groups'=> ['configuracion', 'calidad', 'idioma', 'tipoDescarga']]);
                return new Response($configuracion);
            }
            // no actualiza idioma, calidad y tipoDescarga :(
            if ($request->isMethod("PUT")) {
                $bodyData = $request->getContent();

                $newConfiguracion = $serializer->deserialize($bodyData, Configuracion::class, 'json');
                $configuracion->setAutoplay($newConfiguracion->isAutoplay());
                $configuracion->setAjuste($newConfiguracion->isAjuste());
                $configuracion->setNormalizacion($newConfiguracion->isNormalizacion());
                //$configuracion->setCalidad($newConfiguracion->getCalidad());
                //$configuracion->setIdioma($newConfiguracion->getIdioma());
                //$configuracion->setTipoDescarga($newConfiguracion->getTipoDescarga());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($configuracion);
                $entityManager->flush();
                $configuracionSerialized = $serializer->serialize($configuracion, 'json', ['groups' => ['configuracion', 'usuario']]);

                return new Response($configuracionSerialized);
            }
        }
    }
}
