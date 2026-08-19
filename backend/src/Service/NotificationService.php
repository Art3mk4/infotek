<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Book;
use App\Domain\SubscriptionRepositoryInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Notification service - sends SMS notifications to author subscribers via smspilot.ru.
 *
 * SMS failures are logged and must never break the book-creation flow.
 */
final class NotificationService
{
    private const SMSPILOT_URL = 'https://smspilot.ru/api.php';

    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private LoggerInterface $logger,
        private string $apiKey,
    ) {
    }

    public function notifySubscribers(Book $book): void
    {
        if (empty($book->authors)) {
            $this->logger->info('No authors on book, skipping SMS notifications', ['book_id' => $book->id]);
            return;
        }

        $authorIds = array_map(static fn($author) => $author->id, $book->authors);
        $subscriptions = $this->subscriptionRepository->findByAuthorIds($authorIds);

        if (empty($subscriptions)) {
            $this->logger->info('No subscribers found for book authors', [
                'book_id' => $book->id,
                'author_ids' => $authorIds,
            ]);
            return;
        }

        $phones = array_unique(array_map(static fn($subscription) => $subscription->phone, $subscriptions));
        $authorNames = implode(', ', array_map(static fn($author) => $author->full_name, $book->authors));
        $message = sprintf(
            'New book published: "%s" by %s (%d)',
            $book->title,
            $authorNames,
            $book->year,
        );

        foreach ($phones as $phone) {
            try {
                $this->sendSms($phone, $message);
                $this->logger->info('SMS sent successfully', [
                    'phone' => $phone,
                    'book_id' => $book->id,
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Failed to send SMS', [
                    'phone' => $phone,
                    'book_id' => $book->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function sendSms(string $phone, string $message): void
    {
        // Emulator mode: log the SMS without making an external request.
        if ($this->apiKey === 'emulator') {
            $this->logger->info('SMS emulator mode — would send SMS to smspilot.ru', [
                'phone' => $phone,
                'message' => $message,
            ]);
            return;
        }

        $payload = http_build_query([
            'send' => $message,
            'to' => $phone,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ]);

        $request = $this->requestFactory
            ->createRequest('POST', self::SMSPILOT_URL)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody($this->streamFactory->createStream($payload));

        $response = $this->httpClient->sendRequest($request);
        $body = (string) $response->getBody();
        $result = json_decode($body, true);

        if (!is_array($result) || empty($result['send'])) {
            throw new \RuntimeException('SMS sending failed: ' . $body);
        }
    }
}
