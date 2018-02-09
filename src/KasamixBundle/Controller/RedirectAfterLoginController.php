<?php

namespace KasamixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RedirectAfterLoginController extends Controller
{
    public function indexAction()
    {
        if ($this->isGranted('ROLE_CLIENT')) {
            $url = $this->generateUrl('client_dashboard');
        } elseif ($this->isGranted('ROLE_PROMOTOR')) {
            $url = $this->generateUrl('promotor_dashboard');
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $url = $this->generateUrl('admin_dashboard');
        } elseif ($this->isGranted('ROLE_USER')){
            $url = $this->generateUrl('user_dashboard');
        } else {
            $url = $this->generateUrl('fos_user_security_login');
        }

        return $this->redirect($url);
    }
}
