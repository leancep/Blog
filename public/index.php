<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
session_start();
$app = AppFactory::create();

// if (PHP_SAPI == 'cli-server') {
//     $url  = parse_url($_SERVER['REQUEST_URI']);
//     $file = __DIR__ . $url['path'];
//     if (is_file($file)) return false;
// }
$app->get('/', function (Request $request, Response $response, array $args) {
    $index= new \Library\TemplateEngine("../temp/index.html");    
    $response->getBody()->write($index->render());
    return $response;
});

$app->get('/login', function (Request $request, Response $response, array $args) {
    $login= new \Library\TemplateEngine("../temp/login.html");    
    $response->getBody()->write($login->render());
    return $response;
});

$app->post('/login', function (Request $request, Response $response, array $args) {
    $_SESSION["log"]=False;
    $user= new \Library\UserService();
    if ($user->userExists($_POST["usuario"])==True){
        $_SESSION["log"]=True;
        $_SESSION["usuario"]=$_POST["usuario"];
    }
    if ($_SESSION["log"]==True){
        $response= $response-> withStatus(302);
        $response= $response-> withHeader('Location', 'me');
    }else{
        $response= $response-> withStatus(302);
        $response= $response-> withHeader('Location', '/');
        }

    return $response;
});

$app->get('/register', function (Request $request, Response $response, array $args) {
    $register= new \Library\TemplateEngine("../temp/register.html");    
    $response->getBody()->write($register->render());
    return $response;
});

$app->post('/register', function (Request $request, Response $response, array $args) {
    $register= new \Library\UserService();
    if ($register->saveUser($_POST["usernew"])==True){
        $regOk= new \Library\TemplateEngine("../temp/regok.html");
         $response->getBody()->write($regOk->render());
    }else{
        $regfail= new \Library\TemplateEngine("../temp/regfail.html");
         $response->getBody()->write($regfail->render());
    }

    return $response;
});

$app->get('/me', function (Request $request, Response $response, array $args) {
    $my= new \Library\BlogService();
    $listaPost= $my->getAllPosts($_SESSION["usuario"]);
    $str="";
    foreach ($listaPost as $posts){
        $add= new \Library\TemplateEngine("../temp/mypost.html");
        $add-> addVariable("post",$posts);
        $str.=$add->render();
    }

    $blog= new \Library\TemplateEngine("../temp/myblogmodel.html");
    $blog->addVariable("posteos",$str);
    $response->getBody()->write($blog->render());
    return $response;
});

$app->get('/newpost', function (Request $request, Response $response, array $args) {
    $new= new \Library\TemplateEngine("../temp/newpost.html");   
    $response->getBody()->write($new->render());
    return $response;
});

$app->post('/newpost', function (Request $request, Response $response, array $args) {
    $new= new \Library\BlogService();
    $new->savePost($_POST["posteo"],$_SESSION["usuario"]);  
    $response= $response-> withStatus(302);
    $response= $response-> withHeader('Location', 'me');
    return $response;
});

$app->get('/user/{user}', function (Request $request, Response $response, array $args) {
    $name = $args["user"];
    $allPosts= new \Library\BlogService();
    $posteos= $allPosts->getAllPosts($name);
    $str="";
    foreach ($posteos as $post){
        $posting= new \Library\TemplateEngine("../temp/userblog.html");
        $posting-> addVariable("post",$post);
        $str.=$posting->render();
        }

    $te= new \Library\TemplateEngine("../temp/userblogmodel.html");
    $te-> addVariable("posteos",$str);
    $response->getBody()->write($te->render());
    return $response;
});

$app->get('/inicio', function (Request $request, Response $response, array $args) {
    $Users= new \Library\UserService();
    $usuarios=$Users->getAllUsers();
    $str="";
    foreach ($usuarios as $users){
        $new= new \Library\TemplateEngine("../temp/inicio.html");
        $new->addVariable("users",$users);
        $str.=$new->render();
    }
    $inicio= new \Library\TemplateEngine("../temp/iniciomodel.html");
    $inicio->addVariable("users",$str);
    $response->getBody()->write($inicio->render());
    return $response;
});


$app->run();