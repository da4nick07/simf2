<?php
namespace App\Service;

use App\Entity\Comment;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private $client;
    private $endpoint;

    public function __construct(HttpClientInterface $client, string $akismetKey)
    {
        $this->client = $client;
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
    }

    /**
     * @return int Spam score: 0: not spam, 1: ham, 2: blatant spam
     *
     * @throws \RuntimeException if the call did not work
     */
    public function getSpamScore(Comment $comment, array $context): int
    {
        // реального обращения к внешнему сервису нет
        // потому - заглушка
        if ( stripos( $comment->getBody(), 'spam' ) <> 0) {
            return 2;
        } elseif ( stripos( $comment->getBody(), 'ham' ) <> 0) {
            return 1;
        }
        return 0;

        $user = $comment->getUser();
        $response = $this->client->request('POST', $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => 'https://guestbook.example.com',
                'comment_type' => 'comment',
                'comment_author' => $user,
                'comment_author_email' => $user->getEmail(),
                'comment_content' => $comment->getBody(),
//                'comment_date_gmt' => $comment->getCreatedAt()->format('c'), // Дата в формате стандарта ISO 8601
                'comment_date_gmt' => (new \DateTime)->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }

    public function getEndpoint() : string
    {
        return $this->endpoint;
    }
}