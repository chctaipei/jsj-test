<?php
    include "autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Event\CompleteEvent;
    use GuzzleHttp\Event\ErrorEvent;

    $options = [
        'pool_size' => 20,
        'complete'  => function (CompleteEvent $event) {
            $data = json_decode($event->getResponse()->getBody(), 1);
            print_r($data);
        },
        'error' => function (ErrorEvent $event) {
            error_log('Request failed: ' . $event->getRequest()->getUrl() . "\n");
            error_log($event->getException());
        }
    ];

    $client = new Client();

    // $link = 'http://ecshweb.pchome.com.tw/search/v3.3/all/results?q=%E7%89%99%E8%86%8F&page=1&sort=sale/dc&type=forsale';
    $link = 'http://ecshweb.pchome.com.tw/search/v3.3/all/results?' .  http_build_query(['q' => $argv[1], 'page' => 1]);

    $requests[] = $client->createRequest('GET', $link);

    $pool = new Pool($client, $requests, $options);
    $pool->wait();
?>
