<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Queue;
use App\Models\QueueMusic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create()
            ->each(static function (User $user) {
                $user->queues()->save(Queue::factory()->make());
                foreach ($user->queues()->cursor() as $queue) {
                    $musics = $queue->musics();
                    $musics->saveMany(QueueMusic::factory(10)->make());

                    $musics->each(static function (QueueMusic $music) use ($queue) {
                        $music->order = $queue->nextOrder();
                        $music->save();
                    });
                }
            });
    }
}
