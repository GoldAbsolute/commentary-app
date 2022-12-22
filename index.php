<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Comment\CommentMapper;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$view = new Environment($loader);

// Env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn = $_ENV['DB_DSN'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

// DB
try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo $exception->getMessage();
    die();
}

$commentMapper = new CommentMapper($connection);

// Create app
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('index.html');
    $response->getBody()->write($body);
    return $response;
});

$app->get('/api/get', function (Request $request, Response $response, $args) use ($commentMapper) {
    try {
        $comments = $commentMapper->apiGet();
        $response->getBody()->write(json_encode($comments));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" =>$e->getMessage()
        ];
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});
$app->post('/api/post', function (Request $request, Response $response, $args) use ($commentMapper) {
    $parsed_body = $request->getParsedBody();
    $name = $parsed_body['name'];
    $text = $parsed_body["text"];
    try {
        $result = $commentMapper->apiPost($name, $text);
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" =>$e->getMessage()
        ];
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

// Run
$app->run();