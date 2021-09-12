<?php


namespace App\Controller;


use App\Entity\FbAuthor;
use App\Repository\FbAuthorRepository;
use App\Service\FlibustaClient;
use App\View\FbAuthorView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    private FbAuthorRepository $authorRepository;
    private FlibustaClient $client;

    public function __construct(FbAuthorRepository $authorRepository, FlibustaClient $client)
    {
        $this->authorRepository = $authorRepository;
        $this->client = $client;
    }

    #[Route('/', name: 'api_root')]
    public function indexAction(): Response
    {
        return new JsonResponse(['data' => 'no-no']);
    }

    #[Route('/authors', name: 'api_fb_authors')]
    public function authorsAction(): Response
    {
        $entries = $this->authorRepository->allAuthors();

        $views = array_map(function(FbAuthor $author) {
            return FbAuthorView::fromEntity($author);
        }, $entries);

        return new JsonResponse(['authors' => $views]);
    }

    #[Route('/authors/add', name: 'api_fb_authors_add', methods: ['POST'])]
    public function addAuthorAction(Request $request): Response
    {
        $postData = @json_decode($request->getContent(), true);
        $guid = $postData['guid'];
        if (!$guid) return new JsonResponse([], 500);

        $authorName = $this->client->fetchAuthorName($guid);

        if (!$authorName) return new JsonResponse([], 500);

        $this->authorRepository->addAuthor($guid, $authorName);

        return new JsonResponse(['json' => $authorName]);
    }
}
