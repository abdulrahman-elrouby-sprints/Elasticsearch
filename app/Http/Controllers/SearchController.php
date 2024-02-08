<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use App\Product;

class SearchController extends Controller
{
    protected $client;

    public function __construct()
    {
       $this->client =  ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('query');

        if(!$this->client->indices()->exists(['index' => 'products'])){
            Product::createIndex();
        }
        $params = [
            'index' => 'products',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title'],
                    ],
                ],
            ],
            'filter' => [
                'range' => [
                    'price' => [
                        'gte' => $request->max,  // Greater than or equal to 10
                        'lte' => $request->min  // Less than or equal to 50
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->search($params);

           $ids = $response['source'];

           $products = Product::findOrfail()
        } catch (\Exception $e) {
            // Handle the exception
            return response()->view('error_page', ['message' => 'An error occurred while searching.']);
        }
        return $response;
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        $params = [
            'index' => 'products',
            'body' => [
                'suggest' => [
                    'suggestions' => [
                        'prefix' => $query,
                        'completion' => [
                            'field' => 'title',
                            'size' => 5
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);

        // Extract and format autocomplete suggestions from the Elasticsearch response
        $suggestions = $response['suggest']['suggestions'][0]['options'];
        $suggestions = array_column($suggestions, 'text');

        return $suggestions;

    }
}
