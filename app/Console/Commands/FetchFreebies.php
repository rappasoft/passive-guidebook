<?php

namespace App\Console\Commands;

use App\Models\Freebie;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchFreebies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-freebies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch freebies from Reddit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fetch('new');
        $this->fetch('hot');
    }

    private function fetch(string $type)
    {
        DB::beginTransaction();

        try {
            $response = Http::withHeader('User-Agent', 'rappasoft/fetch-reddit')
                ->get('https://www.reddit.com/r/freebies/'.$type.'.json')->throw()->json() ?? [];

            $contents = collect($response['data']['children'] ?? []);

            $this->line('Found '.$response['data']['dist'].' results.');

            if ($contents->count()) {
                foreach ($contents as $content) {
                    $content = $content['data'];

                    Freebie::firstOrCreate([
                        'vendor_id' => $content['id'],
                    ], [
                        'name' => $content['title'],
                        'url' => $content['url'],
                    ]);
                }
            }
        } catch (Exception $e) {
            debug('Freebies Fetch Error: '.$e->getMessage());

            DB::rollBack();
        }

        DB::commit();
    }
}
