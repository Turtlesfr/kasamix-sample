<?php

namespace KasamixBundle\Controller\Client;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use KasamixBundle\Entity\Accommodation;
use KasamixBundle\Entity\Room;
use KasamixBundle\Entity\RoomModel;
use KasamixBundle\Entity\Covering;
use KasamixBundle\Entity\SurfaceModelCovering;
use KasamixBundle\Entity\Surface;
use KasamixBundle\Form\RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * ClientRoom controller.
 *
 */
class ClientRoomController extends Controller
{
    /**
     * Lists all room entities.
     *
     */
    public function indexAction(Accommodation $accommodation)
    {
        $em = $this->getDoctrine()->getManager();

        $rooms = $em->getRepository(Room::class)->getRoomListByAccommodationWithTheirValue($accommodation);
        return $this->render(
            '@Kasamix/client/room/index.html.twig',
            [
                'rooms' => $rooms,
            ]
        );

        //return $this->json(['data' => $rooms]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderListAction($acco_id)
    {
        $em = $this->getDoctrine()->getManager();

        $rooms = $em->getRepository(Room::class)->getRoomListByAccommodationId($acco_id);
        return $this->render(
            '@Kasamix/client/room/index.html.twig',
            [
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * Lists all room entities.
     *
     */
    public function getListAction(Accommodation $accommodation)
    {
        $em = $this->getDoctrine()->getManager();

        $rooms = $em->getRepository(Room::class)->getRoomListByAccommodation($accommodation);

        return $this->json(['data' => $rooms]);
    }

    /**
     * @param Room $room
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSurfacesAction(Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $surfaces = $em->getRepository(Surface::class)->getListByRoom($room);

        return $this->json(['data' => $surfaces]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveRoomSurfacesAction(Request $request)
    {
        $roomName = $request->get('roomName');
        $data = $request->get('data') ?? [];
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Surface::class)->saveSurfaces($data, $roomName);

        return $this->json(['status' => !$result ? 'error' : 'success', 'roomName' => $roomName]);
    }

    /**
     * @param RoomModel $roomModel Room $room
     * @return Response
     * @ParamConverter("roomModel", options={"mapping": {"roommodelid": "id"}})
     * @ParamConverter("room", class="KasamixBundle:Room", options={"mapping": {"roomid": "id"}})
     */
    public function configAction(RoomModel $roomModel, Room $room): Response
    {
        // RETRIEVE FirstSceneName to load
        $firstSceneName = '';
        $dataChoice = [];
        $surfaces = $room->getSurface();
        foreach($surfaces as $surface)
        {
            $entityManager = $this->getDoctrine()->getManager();
            //$dataSceneName[] = str_replace('.png','',$entityManager->getRepository(Surface::class)->getSelectedOrDefaultCovering($surface));
            $dataChoice[] = $entityManager->getRepository(Surface::class)->getSelectedOrDefaultCovering($surface);
            //return $this->json(['data' => $accomodations]);
        }
            //$firstSceneName = str_replace('.png','',$dataSceneName[0]) . '_' . str_replace('.png','',$dataSceneName[1]);
        $count = count($dataChoice);
        $i = 0;
        foreach ($dataChoice as $key => $entry)
        {
            if($i === 0)
            {
                $firstSceneName .= $entry['picture'];
            }
            else
            {
                $firstSceneName .= '_'.$entry['picture'];
            }
            $i++;
        }
        $entityManager = $this->getDoctrine()->getManager();
        $list = $entityManager->getRepository(SurfaceModelCovering::class)->getCompilationList(
            $roomModel->getId()
        );

        $listOfImages = $this->get('image_composition_service')->prepareListOfCommands($list);
        $assetUrls = $this->container->get('assets.packages');
        $compiled = [];

        foreach ($listOfImages as $key => $compile) {
            $count = count($compile);
            $name = '';

            for ($i = 0; $i < $count; $i++) {
                $var = str_replace('.png', '', $compile[$i][key($compile[$i])]);

                if ($i === 0) {
                    $name .= $var;
                } else {
                    $name .= '_' . $var;
                }
            }
            //$asset_path = $this->get('assets.packages')->getUrl($path);
            $path_p = sprintf('upload/compiled/room_%d/%s.%s', $roomModel->getId(), $name, 'jpg');
            $path = $this->container->get('assets.packages')->getUrl($path_p);
            //$path = sprintf('/current/web/upload/compiled/room_%d/%s.%s', $roomModel->getId(), $name, 'jpg');
            $values = [
                'hfov'              => 100,
                'pitch'             => 0,
                'yaw'               => 0,
                'type'              => 'equirectangular',
                'panorama'          => $assetUrls->getUrl($path),
                'autoLoad'          => true,
                'sceneFadeDuration' => 1000,
                'name'              => $name,
            ];
            if ($key === 0) {
                $compiled['default'] = [
                    'firstScene'        => $firstSceneName,
                    'sceneFadeDuration' => 1000,
                    'autoload'          => true,
                    "showControls"      => false,
                ];
            }
            $compiled['scenes'][$name] = $values;
        }
        return $this->render('@Kasamix/client/room/config.html.twig', [
            'compiled'          => $compiled,
            'roomModel'         => $roomModel,
            'room'              => $room,
            'firstSceneName'    => $firstSceneName,
            'selections'        => $dataChoice,
        ]);
    }
    public function ajaxUpdateSurfaceAction(Request $request)
    {
        $surfaceId = $request->get('surfaceId');
        $coveringId = $request->get('coveringId');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Surface::class)->saveUserChoice((int)$surfaceId, (int)$coveringId);

        return $this->json(['status' => !$result ? 'error' : 'success :'.$result, 'surfaceId' => $surfaceId]);
    }
    public function ajaxUpdateSurfacePriceAction(Request $request)
    {
        $surfaceId = $request->get('surfaceId');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Surface::class)->saveSurfacePrice((int)$surfaceId);

        return $this->json(['status' => !$result ? 'error' : 'success :'.$result]);
    }
    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCoveringAction(Request $request)
    {
        $coveringId = $request->get('coveringId');
        $covering = $this->getDoctrine()->getRepository(Covering::class)->find($coveringId);

        $cName              = $covering->getTitle();
        $cThumb             = $covering->getThumb();
        $cDescription       = $covering->getDescription();
        $cAdditionalPrice   = $covering->getAdditionalPrice();
        $cReference         = $covering->getReference();
        $cId                = $covering->getId();

        return new JsonResponse(
            [
                'id'               => $cId,
                'title'             => $cName,
                'thumb'             => $cThumb,
                'description'       => $cDescription,    
                'additionalPrice'   => $cAdditionalPrice,
                'reference'        => $cReference,
            ]
        );
    }
}
