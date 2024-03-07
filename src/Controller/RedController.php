<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedController extends RedirectController
{
    /**
     * @Route("/{any}", name="not_found", requirements={"any"=".+"})
     */
    public function redirigirRuta(Request $request): Response
    {
        // Redirige a la ruta /info
        return $this->redirectAction($request, '/info');
    }
}
