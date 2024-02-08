<?php

namespace App\Jobs;

use App\Product;
use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Elasticsearch\ClientBuilder;

class IndexProductElasticsearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     * @param Client $client
     */
    public function handle()
    {
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();

        $params = [
            'index' => 'products',
            'id' => $this->product->id,
            'body' => [
                'title' => $this->product->title,
                'price' => $this->product->price,
                'published_at' => $this->product->published_at
            ]
        ];

        $client->index($params);
    }
}