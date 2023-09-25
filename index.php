<?php

use Congress\Lib\Helpers\URLGenerator;
use PComp\{View, Component, HeadManager, StyleManager, ScriptManager};

require_once "vendor/autoload.php";

$configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/congress_config.ini", true);
URLGenerator::$useFriendlyUrls = (bool)($configs['urls']['usefriendly']);

class PageNotFound extends Component
{
	protected function setUp()
	{
		HeadManager::$title = "Erro 404";	
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
	}	

	protected function markup() : Component
	{
		return View::text("Página não encontrada!");
	}
}

$urlPath = strtolower($_GET['page'] ?? '');

if ($urlPath && $urlPath[0] == '/')
	$urlPath = substr($urlPath, 1);

if (!$urlPath)
	$urlPath = '/';

if ($urlPath && $urlPath[strlen($urlPath) - 1] !== '/')
	$urlPath .= '/';

$paths = explode('/', $urlPath);

$pageClass = null;

$currentNamespace = require_once "app/ns.php";
$finalRoutePaths = [];
$matches = [];

$layouts = [];
$errorPages = [];

$routesStatus = array_fill(0, count($paths), null);
foreach ($paths as $pIndex => $path)
{
	if (array_key_exists('__layout', $currentNamespace) && !in_array($currentNamespace['__layout'], $layouts))
		$layouts[] = $currentNamespace['__layout'];

	if (array_key_exists('__error', $currentNamespace) && !in_array($currentNamespace['__error'], $layouts))
		$errorPages[] = $currentNamespace['__error'];

	if (array_key_exists('/' . $path, $currentNamespace))
	{
		if (is_callable($currentNamespace['/' . $path]))
		{
			$routesStatus[$pIndex] = true;
			$currentNamespace = ($currentNamespace['/' . $path])();
			$finalRoutePaths[] = $path;
		}
		else
		{
			$routesStatus[$pIndex] = true;
			$pageClass = $currentNamespace['/' . $path];
			$finalRoutePaths[] = $path;
			$currentNamespace = [];
		}
	}
	else
	{
		foreach (array_keys($currentNamespace) as $key)
			if (preg_match('/\/\[\w+\]/', $key) !== 0)
			{
				if (is_callable($currentNamespace[$key]))
				{
					$routesStatus[$pIndex] = true;
					$currentNamespace = ($currentNamespace[$key])();
					$finalRoutePaths[] = $key;
					$matches[] = $path;
				}
				else
				{
					
					$routesStatus[$pIndex] = true;
					$pageClass = $currentNamespace[$key];
					$finalRoutePaths[] = $key;
					$matches[] = $path;
					$currentNamespace = [];
					break;
				}
			}
	}

	if (!$routesStatus[$pIndex]) break;
}


function setupSingleLayout(string $currentLayoutClassName, array $nextLayoutClassNames, array $params) : Component
{
	$next = array_shift($nextLayoutClassNames);
	if (isset($next))
		return new $currentLayoutClassName( ...$params, children: [ setupSingleLayout($next, $nextLayoutClassNames, $params) ]);
	else
		return new $currentLayoutClassName( ...$params);
}


function setupLayoutsAndPage(array $layoutsList, string $page, array $params) : Component
{
	$layoutsAndPage = array_merge($layoutsList, [ $page ]);

	$first = array_shift($layoutsAndPage);
	return setupSingleLayout($first, $layoutsAndPage, $params);
}

$pageMessages = !empty($_GET['messages']) ? explode('//', $_GET['messages']) : [];
\PComp\Context::set('page_messages', $pageMessages);

?>
<!DOCTYPE HTML>
<html>
	<?php
	$mainFrameComponents = [];

	try
	{		
		$urlParams = null;
		if (!empty($matches) && !empty($pageClass))
		{
			$paramNames = [];
			preg_match_all('/\[(\w+?)\]/', implode('/', $finalRoutePaths ), $paramNames);			
			$urlParams = array_combine($paramNames[1], $matches);
		}

		$params = is_array($urlParams) ? $urlParams : [];

		$page = isset($pageClass) && class_exists($pageClass) ? 
			setupLayoutsAndPage($layouts, $pageClass, $params)
			:
			setupLayoutsAndPage($layouts, PageNotFound::class, $params);

		$page->prepareSetUp();

		$mainFrameComponents = [ $page ];
	}
	catch (\Exception $e)
	{
		$lastErrPage = array_pop($errorPages);
		$page = isset($lastErrPage) && class_exists($lastErrPage) ? 
		setupLayoutsAndPage($layouts, $lastErrPage, [ 'exception' => $e ])
		:
		setupLayoutsAndPage($layouts, PageNotFound::class, $params);

		$page->prepareSetUp();

		$mainFrameComponents = [ $page ];
	}
	?>
	<head>
		<meta charset="utf8"/>
		<link rel="stylesheet" href="assets/twoutput.css" />
		<?= HeadManager::getHeadText() ?>
		<?= StyleManager::getStylesText() ?>
	</head>
	<body>
		<?php View::render($mainFrameComponents); ?>
	</body>
	<?= ScriptManager::getScriptText() ?>
</html>
