<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ProfileController extends AbstractController
{

    public function index($userManager): Response
    {
        $userObject=$this->getUser();
        $username = $userObject->getUsername();
        if($username == 'johndoe')
        {
            $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
            $user->setRoles( array('ROLE_ADMIN') );
            $userManager->updateUser($user, true);
        }
        // BAD - $user->getRoles() will not know about the role hierarchy
        $hasAccess = in_array('ROLE_ADMIN', $user->getRoles());

// GOOD - use of the normal security methods
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return new Response('Well hi there '.$user->getUserIdentifier());
    }
}