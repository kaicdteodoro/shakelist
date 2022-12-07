<?php

namespace App\Services;

use App\Models\Queue;
use Mockery\Exception;
use App\Models\QueueMusic;
use Illuminate\Database\Eloquent\Builder;


class QueueService
{
    public const array_default = [
        'success' => true,
        'data' => [],
        'message' => '',
        'code' => null
    ];

    private array $response;

    private Queue $queue;
    private QueueMusic $music;

    public function __construct(Queue $queue, QueueMusic $music)
    {
        $this->queue = $queue;
        $this->music = $music;
        $this->clearResponse();
    }

    private function clearResponse(): void
    {
        $this->response = self::array_default;
    }

    private function setDataResponse(array $data = []): void
    {
        $this->clearResponse();
        if (!empty($data)) {
            $this->response['data'] = $data;
        }
    }

    private function setErrorResponse(string $message, int $code): void
    {
        $this->clearResponse();
        $this->response['code'] = $code;
        $this->response['success'] = false;
        $this->response['message'] = $message;
    }

    private function responseDefault(): array
    {
        return response_default(
            $this->response['success'],
            $this->response['message'],
            $this->response['code'],
            $this->response['data'],
        );
    }

    public function getQueue(array $ids = []): array
    {
        try {
            $queues = $this->queue
                ->queryWithUser()
                ->when(
                    !empty($ids),
                    static function (Builder $when) use ($ids) {
                        return $when->whereIn('id', $ids);
                    }
                )
                ->orderBy('created_at', 'DESC')
                ->get()
                ->toArray();

            $this->setDataResponse($queues);
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function createQueue(array $inputs): array
    {
        try {
            $queue = $this->queue->query()->create($inputs);
            $this->setDataResponse($queue->toArray());
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function updateQueue(int $queue_id, array $inputs): array
    {
        try {
            $this->queue
                ->queryWithUser()
                ->find($queue_id)
                ?->update($inputs);

            $this->setDataResponse();
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function deleteQueue(int $queue_id): array
    {
        try {
            $this->queue
                ->queryWithUser()
                ->find($queue_id)?->delete();
            $this->setDataResponse();
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function getQueueMusic(int $queue_id, array $ids = []): array
    {
        try {
            $queues = $this->music->queryWithQueue($queue_id)
                ->when(
                    !empty($ids),
                    static function (Builder $when) use ($ids) {
                        return $when->whereIn('id', $ids);
                    }
                )
                ->orderBy("order")
                ->get()
                ->toArray();

            $this->setDataResponse($queues);
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function createMusicQueue(int $queue_id, array $music): array
    {
        try {
            $queue = $this->queue->query()->find($queue_id);
            if (!array_key_exists('queue_id', $music)) {
                $music['queue_id'] = $queue->id;
            }

            $music['order'] = $queue->nextOrder();
            $newMusic = $this->music->query()->create($music);
            $this->setDataResponse($newMusic->toArray());
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function updateQueueMusic(int $queue_id, int $mucis_id, array $inputs): array
    {
        try {
            $this->music->queryWithQueue($queue_id)
                ->where('id', $mucis_id)
                ?->update($inputs);

            $this->setDataResponse();
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }

    public function deleteQueueMusic(int $queue_id, int $music_id): array
    {
        try {
            $this->music->queryWithQueue($queue_id)
                ->find($music_id)
                ?->delete();
            $this->setDataResponse();
        } catch (Exception $exception) {
            $this->setErrorResponse(
                $exception->getMessage(),
                $exception->getCode() ?: 500
            );
        }

        return $this->responseDefault();
    }


    public static function generatePlaylist(int $queue_id): bool
    {
        try {
            $queue = Queue::query()->find($queue_id);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}