<?php

// load autoloader (delete as appropriate)
if (@include(__DIR__.'/lib/Tonic/Autoloader.php')) { // use Tonic autoloader
    new Tonic\Autoloader('resources'); // add another namespace
}

$config = array(
    'load' => array(
        __DIR__.'/resources/*.php', // load resources
    )
);

$app = new Tonic\Application($config);

#echo $app; die;

$request = new Tonic\Request();

#echo $request; die;

try {

    $resource = $app->getResource($request);

    #echo $resources; die;

    $response = $resource->exec();

} catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response(404, $e->getMessage());

} catch (Tonic\UnauthorizedException $e) {
    $response = new Tonic\Response(401, $e->getMessage());
    $response->wwwAuthenticate = 'Basic realm="My Realm"';

} catch (Tonic\Exception $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage());
}

#echo $response;

$response->output();
