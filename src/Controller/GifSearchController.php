<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

class GifSearchController extends AbstractController
{
    #[Route('/gif/search', name: 'gif_search')]
    public function index(Request $request): Response
    {
        $query = $request->query->get('query', 'random');  
        $gifs = [];

        if ($query) {
            $client = HttpClient::create();

            $response = $client->request('GET', 'https://tenor.googleapis.com/v2/search', [
                'query' => [
                    'key' => 'AIzaSyCTNx8GDh1yZskluq5M09DwbanZMrdtIxs',  
                    'q' => $query,  
                    'limit' => 18,  
                ],
            ]);

            $data = $response->toArray();

            $gifs = $data['results'] ?? [];
        }

        return $this->render('gif_search/index.html.twig', [
            'gifs' => $gifs,
            'query' => $query,
        ]);
    }
}
