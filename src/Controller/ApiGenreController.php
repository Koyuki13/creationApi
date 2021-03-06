<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
                'groups' => ['groupe']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_show", methods={"GET"})
     * @param Genre $genre
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listeGenreSimple']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genre", name="api_genre_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $genre = $serializer->deserialize($data, Genre::class, 'json');

        //gestion des erreurs de validation
        $erreurs = $validator->validate($genre);
        dd($erreurs);
        if(count($erreurs) > 0) {
            $erreurJson = $serializer->serialize($erreurs, 'json');
            return new JsonResponse($erreurJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($genre);
        $entityManager->flush();

        return new JsonResponse(
            "Le nouveau genre a bien été créé",
            Response::HTTP_CREATED,
            ["location" => "/api/genre/".$genre->getId()],
            true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_delete", methods={"DELETE"})
     * @param Genre $genre
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function delete(Genre $genre)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($genre);
        $entityManager->flush();

        return new JsonResponse("Le genre a bien été supprimé", 200, []);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_edit", methods={"PUT"})
     * @param Genre $genre
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function edit(Genre $genre, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);

        //gestion des erreurs de validation
        $erreurs = $validator->validate($genre);
        if(count($erreurs)) {
            $erreurJson = $serializer->serialize($erreurs, 'json');
            return new JsonResponse($erreurJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($genre);
        $entityManager->flush();

        return new JsonResponse("Le genre a bien été modifié", 200, [], true);
    }

}
