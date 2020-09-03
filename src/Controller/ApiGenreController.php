<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genre", name="api_genre", methods={"GET"})
     * @param GenreRepository $genreRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function liste(GenreRepository $genreRepository, SerializerInterface $serializer)
    {
        $genres = $genreRepository->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => ['listeGenreSimple']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }
}
