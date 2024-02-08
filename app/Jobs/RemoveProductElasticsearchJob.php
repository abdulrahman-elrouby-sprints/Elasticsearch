<?php

namespace App\Jobs;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveProductElasticsearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function handle(Client $client)
    {
        $params = [
            'index' => 'products',
            'id' => $this->productId,
        ];

        try {
            $client->delete($params);
        } catch (App\Jobs\Missing404Exception $exception) {
            // Already deleted..
        }
    }
}
