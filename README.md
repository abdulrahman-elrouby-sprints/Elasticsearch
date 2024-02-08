#### Step 1 ( Setup Elasticsearch in Laravel Application )
`composer require elasticsearch/elasticsearch`

#### Step 2 Update Config File for Database
add the below configuration in the connections array in your config/database.php file.

```
'elasticsearch' => [
    'driver' => 'elasticsearch',
    'hosts' => [
        [
            'host' => env('ELASTICSEARCH_HOST', 'localhost'),
            'port' => env('ELASTICSEARCH_PORT', 9200),
        ],
    ],
],
```

#### Step 3 Create a Product model with the createIndex method
* app/Models/Product.php

#### Step 4 Setup Observer to dispatch Indexing Job on Events

**Note**: Registering an observer in Laravel allows you to listen for specific events that occur on your Eloquent models. Observers are useful for performing actions such as dispatching jobs, sending notifications, or logging information when certain events are triggered

**paths:**
* app/Observers/ProductObserver.php
* app/Providers/AppServiceProvider.php

#### Step 5 Create the Jobs to handle the crud operation
* app/Jobs/IndexProductElasticsearchJob.php
* app/Jobs/RemoveProductElasticsearchJob.php
### Step 6 Create the routes and controllers to handle the search operation
* routes/web.php
* app/Http/Controllers/SearchController.php
