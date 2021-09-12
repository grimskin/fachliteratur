<?php


namespace App\Controller;


use App\Entity\FbAuthor;
use App\Repository\FbAuthorRepository;
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

    public function __construct(FbAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
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
        $postData = @json_decode($request->getContent());

        return new JsonResponse($postData);
    }
}
