<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteur", name="api_auteur", methods={"GET"})
     * @param AuteurRepository $auteurRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function liste(AuteurRepository $auteurRepository, SerializerInterface $serializer)
    {
        $auteurs = $auteurRepository->findAll();
        $resultat = $serializer->serialize(
            $auteurs,
            'json',
            [
                'groups' => ['listeAuteurFull']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_show", methods={"GET"})
     * @param Auteur $auteur
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Auteur $auteur, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups' => ['listeAuteurSimple']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/auteur", name="api_auteur_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $auteur = $serializer->deserialize($data, Auteur::class, 'json');

        //gestion des erreurs de validation
        $erreurs = $validator->validate($auteur);
        if(count($erreurs)) {
            $erreurJson = $serializer->serialize($erreurs, 'json');
            return new JsonResponse($erreurJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($auteur);
        $entityManager->flush();

        return new JsonResponse(
            "Le nouveau genre a bien été créé",
            Response::HTTP_CREATED,
            ["location" => "/api/auteur/".$auteur->getId()],
            true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_delete", methods={"DELETE"})
     * @param Auteur $auteur
     * @return JsonResponse
     */
    public function delete(Auteur $auteur)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($auteur);
        $entityManager->flush();

        return new JsonResponse("L auteur a bien été supprimé", 200, []);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_edit", methods={"PUT"})
     * @param Auteur $auteur
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function edit(Auteur $auteur, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Auteur::class, 'json', ['object_to_populate' => $auteur]);

        //gestion des erreurs de validation
        $erreurs = $validator->validate($auteur);
        if(count($erreurs)) {
            $erreurJson = $serializer->serialize($erreurs, 'json');
            return new JsonResponse($erreurJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($auteur);
        $entityManager->flush();

        return new JsonResponse("L auteur a bien été modifié", 200, [], true);
    }

}
