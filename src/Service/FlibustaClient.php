<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class FlibustaClient
{
    private string $rssUrl = 'http://flibusta.is/a/{guid}/rss/';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchAuthorName($guid): string
    {
        try {
            $rss = $this->fetchRss($guid);

            if (!isset($rss['channel']['title'])) throw new \Exception('No channel data');

            $channelName = $rss['channel']['title'];
            $titleSplit = explode('-', $channelName);

            if (!isset($titleSplit[1])) throw new \Exception('Unable to find author name in channel title');

            return trim($titleSplit[1]);
        } catch (\Exception $e) {
            return '';
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
