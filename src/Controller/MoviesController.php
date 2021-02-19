<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MoviesRepository;
use App\Entity\Movies;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class MoviesController extends AbstractController
{
    protected function serializeJson($objet)
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        return $serializer->serialize($objet, 'json');
    }
     /**
     * @Route("/", name="movies")
     */
    public function index(): Response
    {
        $movies = $this->getDoctrine()
            ->getRepository(Movies::class)
            ->findAll();
        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $movies
        ]);
    }
   
    /**
     * @Route("/movie/get/{id}", name="getMoviebyId", methods={"GET"})
     * @param MoviesRepository $moviesRepository
     * @return Response
     */
    public function getMovieById($id,MoviesRepository $moviesRepository): Response
    {
        $movies = $moviesRepository->findBy(['id'=> $id]);
        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/json/getallmovies", name="movies_json", methods={"GET"})
     * @param MoviesRepository $moviesRepository
     * @param Request $request
     * @return Response
     */
    public function moviesJson(MoviesRepository $moviesRepository, Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Movies::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($moviesRepository->findBy($filter)));
    }
    /**
     * @Route("/json/movie/get/{id}", name="getMovieById_json", methods={"GET"})
     * @param $id
     * @param MoviesRepository $moviesRepository
     * @return Response
     */
    public function getMoviebyIdJson($id, MoviesRepository $moviesRepository): Response
    {
        $error = [];
        $response = new Response();
        $movie = $moviesRepository->findBy(
            [
                'id' => $id
            ]
        );
        if (empty($movie)){
            array_push($error, "ce film n'existe pas");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }else {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent($this->serializeJson($movie));
        }
        return $response;
    }
 
     /**
     * @Route("/api/movies/films/create", name="movies_Films_Create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function moviesCreate(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $newmovie = new Movies();
        $newmovie->setNom($request->request->get("nom"))
            ->setSynopsis($request->request->get("synopsis"))
            ->setType("film");
        $entityManager->persist($newmovie);
        $entityManager->flush();
        $response = new Response();
        $response->setContent('Saved new movie with id ' . $newmovie->getId());
        return $response;
    }

    /**
     * @Route("/api/movies/series/create", name="movies_Series_Create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function seriesCreate(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $newmovie = new Movies();
        $newmovie->setNom($request->request->get("nom"))
            ->setSynopsis($request->request->get("synopsis"))
            ->setType("serie");

        $entityManager->persist($newmovie);
        $entityManager->flush();
        $response = new Response();
        $response->setContent('Saved new series with id ' . $newmovie->getId());
        return $response;
    }
}
