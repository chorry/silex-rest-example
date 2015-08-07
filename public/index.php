<?php
use app\models\News;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new DerAlex\Silex\YamlConfigServiceProvider('../app/config.yaml'));

$entityManager = \app\database\DBManager::getConnection($app);

$app->before(function (Request $request) {
	if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
		$data = json_decode($request->getContent(), true);
		$request->request->replace(is_array($data) ? $data : array());
	}
});

$app->get('/news', function() use ($app, $entityManager){
	return $app->json($entityManager->find(), 200);
});

$app->get('/news/{id}', function($id) use($app, $entityManager) {
	$code = 404;
	if ( $news = $entityManager->find($id) )
		$code = 200;
	return $app->json($news, $code);
});

$app->put('/news/{id}', function(Request $request, $id) use($app, $entityManager){
	$code = 404;
	if ($data = $entityManager->find($id))
	{
		$code = 200;
		$news = new News($data);
		$news->setAttributes( [
			'title' => $request->request->get( 'title' ),
			'body'  => $request->request->get( 'body' ),
		] );
		if (!$news->isValid() || !$entityManager->save($news))
			$code = 400;
	}
	return $app->json($news, $code);
});

$app->post('/news/', function(Request $request) use($app, $entityManager){
	$code = 400;
	$news = new News();
	$news->setAttributes([
		'title' => $request->request->get('title'),
		'body'  => $request->request->get('body'),
	]);

	if ( $news->isValid() && $entityManager->save($news) )
		$code = 200;

	return $app->json($news, $code);
});

$app->delete('/news/{id}', function(Request $request, $id) use($app, $entityManager){
	$code = 404;
	if ($entityManager->find($id))
	{
		$code = 400;
		if ( $entityManager->delete( $id ) )
			$code = 200;
	}
	return $app->json(null, $code);
});

$app->run();