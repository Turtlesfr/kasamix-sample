<?php

namespace KasamixBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Twig\TwigEngine;
use KasamixBundle\Entity\Room;
use KasamixBundle\Entity\User;
use KasamixBundle\Entity\Accommodation;
use KasamixBundle\Entity\Surface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use SymfonyContrib\Bundle\ConfirmBundle\ConfirmBundle;

class ClientDashboardController extends Controller
{
	/*
    public function indexAction()
    {
        return $this->render('@Kasamix/client/Default/index.html.twig', []);
    }
    */
    public function indexAction()
    {
    	$userId = $this->getUserId();
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($userId);
        $accommodation = $em->getRepository(User::class)->getAccommodation($user);
        $acco_status = $em->getRepository(Accommodation::class)->getStatus($accommodation);
        $hasValidated = $em->getRepository(User::class)->find($userId)->getHasApprovedAccommodation();
        
        if($acco_status == 3)
        {
            return $this->render('@Kasamix/client/Default/accommodationMaintenance.html.twig');
        }
        else
        {
            $hasValidated = $em->getRepository(User::class)->find($userId)->getHasApprovedAccommodation();
            if(!$hasValidated)
            {
                return $this->render('@Kasamix/client/Default/accommodationValidation.html.twig');
            }
            else
            {
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
                    '@Kasamix/client/Default/index.html.twig',
                    [
                        'accommodation_prices' => null !== $accommodation_prices ? json_encode($accommodation_prices): 'null',
                    ]
                );
            } 
        }

       
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSurfacePriceAction(Request $request)
    {
        $surfaceId = $request->get('surfaceId');
        $em = $this->getDoctrine()->getManager();
        $surfacePrice = $em->getRepository(Surface::class)->getSurfacePrice($surfaceId);
        return $this->json(['data' => $surfacePrice]);
    }

    public function objectDeleteAction(Request $request)
    {
        $accommodationId = $request->get('accommodationId');
        $statusId = $request->get('statusId');
        $options = [
            'message' => 'Are you sure you want to DELETE this?',
            'warning' => 'This can not be undone!',
            'confirmButtonText' => 'Delete',
            'confirmAction' => [$this, 'delete'],
            'confirmActionArgs' => [
                'accommodationId' => $accommodationId,
                'statusId' => $statusId,
            ],
            'cancelLinkText' => 'Cancel',
            'cancelUrl' => $this->generateUrl('client_dashboard'),
        ];

        return $this->forward('ConfirmBundle:Confirm:confirm', ['options' => $options]);
    }

    public function delete($args)
    {
        return $args;
        // delete object
        // set flash message
        // redirect
    }

    public function objectAjaxValidateAccommodationAction(Request $request)
    {
        $options = [
            'message' => 'Are you sure you want to do this?',
            'warning' => 'This can not be undone!',
            'confirmButtonText' => 'Validate',
            'confirmAction' => [$this, 'ajaxValidateAccommodationAction'],
            'confirmActionArgs' => [
                'object' => $request,
            ],
            'cancelLinkText' => 'Cancel',
            'cancelUrl' => $this->generateUrl('client_dashboard'),
        ];

        return $this->forward('ConfirmBundle:Confirm:confirm', ['options' => $options]);
    }
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxValidateAccommodationAction(Request $request)
    {
        $accommodationId = $request->get('accommodationId');
        $statusId = $request->get('statusId');
        $em = $this->getDoctrine()->getManager();
        $accommodation = $em->getRepository(Accommodation::class)->find($accommodationId);
        $result = $em->getRepository(Accommodation::class)->setStatus($accommodation,$statusId);
        
        return $this->json(['status' => !$result ? 'error' : 'success']);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxAccommodationInformationsValidationAction(Request $request)
    {
        $userId = $request->get('userId');
        $userId = $this->getUserId();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($userId);
        $result = $em->getRepository(User::class)->validateAccommodationInformations($user);

        return $this->json(['status' => !$result ? 'error':'success']);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendEmailAboutAccommodationAction(Request $request)
    {
        $messageData = $request->get('message');
        $adminMail = $this->container->getParameter('admin_mail');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Kasamix - new message')
                    ->setFrom('alexandre@kasamix.com')
                    ->setTo($adminMail)
                    ->setBody("New message : \n".$messageData, "text/plain")
                ;
                $this->get('mailer')->send($message);
        return $this->json(['data' => $messageData,'message'=>$message]);
    }
}
