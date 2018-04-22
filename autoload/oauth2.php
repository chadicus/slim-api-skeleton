<?php

use Chadicus\Slim\OAuth2\Routes\Token;
use Psr\Container\ContainerInterface;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

return [
    'services' => [
        Pdo::class => function (ContainerInterface $container) : Pdo {
            $settings = $container->get('settings')['pdo'];
            return new Pdo($settings);
        },
        Server::class => function (ContainerInterface $container) : Server {
            $storage = $container->get(Pdo::class);
            return new Server(
                $storage,
                ['access_lifetime' => 3600],
                [new ClientCredentials($storage)]
            );
        },
        Token::class => function (ContainerInterface $container) : Token {
            return new Token($container->get(Server::class));
        },
    ],
    'routes' => [
        'methods' => ['POST'],
        'pattern' => '/token',
        'callable' => Token::class,
    ],
];
