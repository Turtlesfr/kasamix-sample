<?php

namespace KasamixBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Twig\TwigEngine;
use KasamixBundle\Entity\Room;
use KasamixBundle\Entity\Accommodation;
use KasamixBundle\Entity\Surface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientValidationController extends Controller
{
    public function indexAction()
    {
    	$userId = $this->getUserId();
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository(Room::class)->getRoomObjectListByUser($userId);
        $accommodation_prices = [];
        foreach($rooms as $room)
        {
            $room_data = [];
            $surfaces = $em->getRepository(Room::class)->find((int)$room['id'])->getSurface();
            foreach($surfaces as $surface)
            {
                //find surface price
                if($surface->getId())
                {
                    $surfacePrice = $em->getRepository(Surface::class)->getSurfacePrice($surface->getId());
                    $room_data[] = [
                                    'id' => $surface->getId(),
                                    'price' => $surfacePrice
                                    ];
                    }
            }
            $accommodation_prices[] =   [ 
                                        'room_id' => $room['id'],
                                        'surfaces' =>$room_data
                                        ];
        }
        return $this->render(
            '@Kasamix/client/Validation/index.html.twig',
            [
                'accommodation_prices' => null !== $accommodation_prices ? json_encode($accommodation_prices): 'null',
            ]
        );
    }

    public function confirmAction()
    {
        $userId = $this->getUserId();
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository(Room::class)->getRoomObjectListByUser($userId);
        $accommodation_prices = [];
        foreach($rooms as $room)
        {
            $room_data = [];
            $surfaces = $em->getRepository(Room::class)->find((int)$room['id'])->getSurface();
            foreach($surfaces as $surface)
            {
                //find surface price
                if($surface->getId())
                {
                    $surfacePrice = $em->getRepository(Surface::class)->getSurfacePrice($surface->getId());
                    $room_data[] = [
                                    'id' => $surface->getId(),
                                    'price' => $surfacePrice
                                    ];
                    }
            }
            $accommodation_prices[] =   [ 
                                        'room_id' => $room['id'],
                                        'surfaces' =>$room_data
                                        ];
        }
        return $this->render(
            '@Kasamix/client/Validation/confirm.html.twig',
            [
                'accommodation_prices' => null !== $accommodation_prices ? json_encode($accommodation_prices): 'null',
            ]
        );
    }
    
    /**
	 * Get user id
	 * @return integer $userId
	 */
	protected function getUserId()
	{
	    $user = $this->get('security.token_storage')->getToken()->getUser();
	    $userId = $user->getId();

	    return $userId;
	}

}
