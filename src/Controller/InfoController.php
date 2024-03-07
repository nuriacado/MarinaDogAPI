<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    /**
     * @Route("/info", methods={"GET"}, name="not_found")
     */
    public function mostrarInfo()
    {
        $metodosCRUD = [
            [
                'metodo' => 'GET',
                'ruta' => '/reservas',
                'descripcion' => 'Obtener todas las reservas',
                'exito' => '200 OK',
                'error' => []
            ],
            [
                'metodo' => 'GET',
                'ruta' => '/reservas/{id}',
                'descripcion' => 'Obtener una reserva por su ID',
                'exito' => '200 OK',
                'error' => ['404 NOT FOUND']
            ],
            [
                'metodo' => 'POST',
                'ruta' => '/reservas',
                'descripcion' => 'Crear una nueva reserva',
                'exito' => '201 Created',
                'error' => ['400 BAD REQUEST', '404 NOT FOUND']
            ],
            [
                'metodo' => 'PUT',
                'ruta' => '/reservas/{id}',
                'descripcion' => 'Modificar una reserva existente',
                'exito' => '200 OK',
                'error' => ['400 BAD REQUEST', '404 NOT FOUND']
            ],
            [
                'metodo' => 'DELETE',
                'ruta' => '/reservas/{id}',
                'descripcion' => 'Eliminar una reserva por su ID',
                'exito' => '200 OK',
                'error' => ['404 NOT FOUND']
            ],
        ];

        // Renderizar la plantilla Twig con los datos
        return $this->render('info.html.twig', [
            'metodosCRUD' => $metodosCRUD,
        ]);
    }
}