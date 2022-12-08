<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Mockery\Exception;
use App\Models\QueueMusic;
use Illuminate\Http\Request;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private QueueService $service;

    public function __construct(QueueService $service)
    {
        $this->service = $service;
    }


    /**
     * @param array $response deve estar de acordo com o pdrão de retorno da service!
     * @return JsonResponse
     */
    private static function responseDefault(array $response): JsonResponse
    {
        try {
            $code = $response["code"];
            unset($response["code"]);
        } catch (Exception $exception) {
            $response = ['message' => 'Internal server Error!'];
        }
        return response()->json($response, $code ?? 500);
    }

    /**
     *
     * Captura a validação par padronizar a mesma no retorno
     * @param Request $request
     * @param array $rules
     * @return array|null
     */
    private function testValidation(Request $request, array $rules): ?array
    {
        try {
            $this->validate($request, $rules);
            return null;
        } catch (ValidationException $e) {
            return response_default(false, $e->getMessage(), 400);
        }
    }


    // Queue session

    public function QueueAll(): JsonResponse
    {
        return self::responseDefault($this->service->getQueue());
    }

    public function QueueCreate(Request $request): JsonResponse
    {
        $rules = Queue::rules;
        $inputs = $request->only(array_keys($rules));
        $inputs['user_id'] = auth()->id();

        $response = $this->testValidation($request, $rules) ?? $this->service->createQueue($inputs);

        return self::responseDefault($response);
    }

    public function QueueFind(int $queue_id): JsonResponse
    {
        return self::responseDefault($this->service->getQueue([$queue_id]));
    }

    public function QueueUpdate(int $queue_id, Request $request): JsonResponse
    {
        $rules = Queue::rulesUpdate;

        $response = $this->testValidation($request, $rules) ??
            $this->service->updateQueue(
                $queue_id,
                $request->only(array_keys($rules))
            );

        return self::responseDefault($response);
    }

    public function QueueDelete(int $queue_id): JsonResponse
    {
        return self::responseDefault($this->service->deleteQueue($queue_id));
    }


    // QueueMusic session

    public function QueueMusicAll(int $queue_id, Request $request): JsonResponse
    {
       $queryParms = $request->only(['show_done']);
        return self::responseDefault($this->service->getQueueMusic($queue_id, [], $queryParms));
    }

    public function QueueMusicCreate(int $queue_id, Request $request): JsonResponse
    {
        $rules = QueueMusic::rules;
        $inputs = $request->only(array_keys($rules));
        $inputs['queue_id'] = $queue_id;

        $response = $this->testValidation($request, $rules) ?? $this->service->createMusicQueue($queue_id, $inputs);

        return self::responseDefault($response);
    }

    public function QueueMusicFind(int $queue_id, int $music_id): JsonResponse
    {
        return self::responseDefault($this->service->getQueueMusic($queue_id, [$music_id]));
    }

    public function QueueMusicUpdate(int $queue_id, int $music_id, Request $request): JsonResponse
    {
        $rules = QueueMusic::rulesUpdate;
        $response = $this->testValidation($request, $rules) ??
            $this->service->updateQueueMusic(
                $queue_id,
                $music_id,
                $request->only(array_keys($rules))
            );

        return self::responseDefault($response);
    }

    public function QueueMusicDelete(int $queue_id, int $music_id): JsonResponse
    {
        return self::responseDefault($this->service->deleteQueueMusic($queue_id, $music_id));
    }

    public function QueueMusicDirection(int $queue_id, int $music_id, string $direction): JsonResponse
    {
        $up = $direction === 'up';
        return self::responseDefault($this->service->turnQueueMusicDirection($queue_id, $music_id, $up));
    }



    // OAuth
    public function OAuthLogin()
    {

    }
}
