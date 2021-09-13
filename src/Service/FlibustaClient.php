<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class FlibustaClient
{
    private string $rssUrl = 'http://flibusta.is/a/{guid}/rss/';

    private HttpClientInterface $client;
    private array $rss;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch($guid): void
    {
        try {
            $this->rss = $this->fetchRss($guid);
        } catch (\Exception $e) {
        }
    }

    public function getAuthorName(): string
    {
        try {
            if (!isset($this->rss['channel']['title'])) throw new \Exception('No channel data');

            $channelName = $this->rss['channel']['title'];
            $titleSplit = explode('-', $channelName);

            if (!isset($titleSplit[1])) throw new \Exception('Unable to find author name in channel title');

            return trim($titleSplit[1]);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getBooks(): array
    {
        try {
            if (!isset($this->rss['channel']['item'])) throw new \Exception('No book data fount');

            return $this->rss['channel']['item'];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchRss($guid): array
    {
        $response = $this->client->request('GET', $this->getRssUrl($guid));

        $xml = simplexml_load_string($response->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);

        return json_decode($json, true);
    }

    private function getRssUrl(string $guid): string
    {
        return str_replace('{guid}', $guid, $this->rssUrl);
    }
}
