<?php


namespace App\Controller;


use App\DTO\BookDTO;
use App\Entity\FbAuthor;
use App\Entity\FbBook;
use App\Repository\FbAuthorRepository;
use App\Repository\FbBookRepository;
use App\Service\FlibustaClient;
use App\Service\FlibustaManager;
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
    private FbBookRepository $bookRepository;
    private FlibustaClient $client;
    private FlibustaManager $manager;

    public function __construct(
        FbAuthorRepository $authorRepository,
        FbBookRepository $bookRepository,
        FlibustaClient $client,
        FlibustaManager $manager
    ) {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
        $this->client = $client;
        $this->manager = $manager;
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

        $this->client->fetch($guid);
        $authorName = $this->client->getAuthorName();

        if (!$authorName) return new JsonResponse([], 500);

        $this->authorRepository->addAuthor($guid, $authorName);

        return new JsonResponse(['json' => $authorName]);
    }

    #[Route('/fetch', name: 'api_fb_fetch', methods: ['GET'])]
    public function fetchAction(Request $request): Response
    {
        $authors = $this->authorRepository->findAll();

        /** @var FbBook[] $lastWeek */
        $lastWeek = [];
        /** @var FbBook[] $preWeek */
        $preWeek = [];

        foreach ($authors as $author) {
            $this->manager->fetchByAuthor($author);

            $lastWeek = array_merge($lastWeek, $this->bookRepository->lastWeekByAuthor($author));
            $preWeek = array_merge($preWeek, $this->bookRepository->preWeekByAuthor($author));
        }

        $lastWeekSerialized = array_map(function(FbBook $book) {
            return BookDTO::fromEntity($book);
        }, $lastWeek);
        $preWeekSerialized = array_map(function(FbBook $book) {
            return BookDTO::fromEntity($book);
        }, $preWeek);


        return new JsonResponse(['last_week' => $lastWeekSerialized, 'pre_week' => $preWeekSerialized]);
    }
}
