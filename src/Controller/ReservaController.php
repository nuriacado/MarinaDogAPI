<?php

// src/Controller/ReservaController.php
namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DetallesReserva;
use App\Entity\Habitacion;

/**
 * @Route("/reservas")
 */
class ReservaController extends AbstractController
{
    private function comprobarClave(EntityManagerInterface $entityManager, $claveApi)
    {
        return $entityManager->getRepository(Usuario::class)->findOneBy(['claveApi' => $claveApi]);
    }

    /**
     * @Route("/{claveApi}", methods={"GET"}, name="reservas_list")
     */
    public function getAll($claveApi, EntityManagerInterface $entityManager): Response
    {
        if ($this->comprobarClave($entityManager, $claveApi)) {
            $emReservas = $entityManager->getRepository(DetallesReserva::class)->findAll();

            $reservas = [];
            if (count($emReservas) > 0) {
                foreach ($emReservas as $reserva) {
                    $reservas[] = $this->modeloReserva($reserva);
                }
            }

            return new JsonResponse($reservas, Response::HTTP_OK);
        }
        return new JsonResponse('', 401);
    }

    private function modeloReserva($reserva)
    {
        return [
            'id' => $reserva->getId(),
            'entrada' => $reserva->getFechaEntrada()->format('d/m/Y'),
            'salida' => $reserva->getFechaSalida()->format('d/m/Y'),
            'habitacion_id' => $reserva->getHabitacion(),
            'nombre' => $reserva->getNombre(),
            'apellidos' => $reserva->getApellidos(),
            'correo' => $reserva->getCorreo(),
            'telefono' => $reserva->getTelefono(),
            'direccion' => $reserva->getDireccion()
        ];
    }

    /**
     * @Route("/{claveApi}/{id}", methods={"GET"}, name="reserva_show", requirements={"id"="\d+"})
     */
    public function getReserva($claveApi, $id, EntityManagerInterface $entityManager): Response
    {
        if ($this->comprobarClave($entityManager, $claveApi)) {
            $emReserva = $entityManager->getRepository(DetallesReserva::class)->find($id);

            if (!$emReserva) {
                return new JsonResponse('', Response::HTTP_NOT_FOUND);
            }

            $reserva = $this->modeloReserva($emReserva);

            return new JsonResponse($reserva, Response::HTTP_OK);
        }

        return new JsonResponse('', 401);
    }

    /**
     * @Route("/{claveApi}", methods={"POST"}, name="reserva_create")
     */
    public function crearReserva($claveApi, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->comprobarClave($entityManager, $claveApi)) {
            $data = json_decode($request->getContent(), true);

            $datos = $this->validarDatos($data['nombre'], $data['correo'], $data['apellidos'], $data['telefono'], $data['direccion']);
            $reserva = $this->comprobarHabitacion($data['habitacion_id'], $data['fecha_entrada'], $data['fecha_salida'], $entityManager);

            if ($reserva && $datos) {
                $habitacionId = $data['habitacion_id'];
                $habitacion = $entityManager->getRepository(Habitacion::class)->find($habitacionId);

                if (!$habitacion) {
                    return new Response('', Response::HTTP_NOT_FOUND);
                }

                $reserva = new DetallesReserva();
                $reserva->setFechaEntrada(new \DateTime($data['fecha_entrada']));
                $reserva->setFechaSalida(new \DateTime($data['fecha_salida']));
                $reserva->setHabitacion($habitacion);
                $reserva->setNombre($data['nombre']);
                $reserva->setApellidos($data['apellidos']);
                $reserva->setCorreo($data['correo']);
                $reserva->setTelefono($data['telefono']);
                $reserva->setDireccion($data['direccion']);

                $entityManager->persist($reserva);
                $entityManager->flush();

                return new Response('', Response::HTTP_CREATED);
            }

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('', 401);
    }

    private function comprobarHabitacion($numHabitacion, $fechaEntrada, $fechaSalida, EntityManagerInterface $entityManager): bool
    {
        if ($fechaEntrada < $fechaSalida) {
            $query = $entityManager->createQuery(
                'SELECT COUNT(d) FROM App\Entity\DetallesReserva d
                LEFT JOIN App\Entity\DetallesReserva d2
                WITH d.habitacion = d2.habitacion
                WHERE (:fechaEntrada BETWEEN d2.fecha_entrada AND d2.fecha_salida 
                OR :fechaSalida BETWEEN d2.fecha_entrada AND d2.fecha_salida) 
                OR (:fechaEntrada <= d2.fecha_entrada AND :fechaSalida >= d2.fecha_salida)
                AND d.habitacion = :numHabitacion'
            );

            $query->setParameter('numHabitacion', $numHabitacion);
            $query->setParameter('fechaEntrada', $fechaEntrada);
            $query->setParameter('fechaSalida', $fechaSalida);

            $numReservas = $query->getSingleScalarResult();

            return $numReservas == 0;
        }
        return false;
    }


    private function validarDatos($nombre, $correo, $apellidos, $telefono, $direccion): bool
    {
        if (strlen($nombre) == 0 || strlen($apellidos) == 0 || strlen($correo) == 0 || strlen($telefono) == 0 || strlen($direccion) == 0) {
            return false;
        } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else if (!preg_match('/^[9|8|6|7][0-9]{8}$/', $telefono)) {
            return false;
        }

        return true;
    }

    /**
     * @Route("/{claveApi}/{id}", methods={"PUT"}, name="reserva_update", requirements={"id"="\d+"})
     */
    public function modificarReserva($claveApi, $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->comprobarClave($entityManager, $claveApi)) {
            $data = json_decode($request->getContent(), true);
            $reserva = $entityManager->getRepository(DetallesReserva::class)->find($id);

            if (!$reserva) {
                return new JsonResponse('', Response::HTTP_NOT_FOUND);
            }

            if (!$this->validarDatos($data['nombre'], $data['correo'], $data['apellidos'], $data['telefono'], $data['direccion'])) {
                return new JsonResponse('', Response::HTTP_BAD_REQUEST);
            }

            $reserva->setNombre($data['nombre']);
            $reserva->setApellidos($data['apellidos']);
            $reserva->setCorreo($data['correo']);
            $reserva->setTelefono($data['telefono']);
            $reserva->setDireccion($data['direccion']);

            $entityManager->flush();

            return new Response('', Response::HTTP_OK);
        }

        return new JsonResponse('', 401);
    }

    /**
     * @Route("/{claveApi}/{id}", methods={"DELETE"}, name="reserva_delete", requirements={"id"="\d+"})
     */
    public function borrarReserva($claveApi, $id, EntityManagerInterface $entityManager): Response
    {
        if ($this->comprobarClave($entityManager, $claveApi)) {
            $reserva = $entityManager->getRepository(DetallesReserva::class)->find($id);

            if (!$reserva) {
                return new JsonResponse([], Response::HTTP_NOT_FOUND);
            }

            $entityManager->remove($reserva);
            $entityManager->flush();

            return new Response('', Response::HTTP_OK);
        }

        return new JsonResponse('', 401);
    }
}
