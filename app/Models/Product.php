<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Elasticsearch\ClientBuilder;

class Product extends Model
{
    protected $fillable = ['title','price','published_at'];
    public static function createIndex()
    {
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();
        $params = [
            'index' => 'products',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'title' => ['type' => 'text'],
                        'price' => ['type' => 'float'],
                        'published_at' => ['type' => 'date'],
                    ],
                ]
            ],
        ];

        try {
            $client->indices()->create($params);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error or display a user-friendly message)
        }
    }
}
