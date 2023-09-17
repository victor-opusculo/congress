<?php
namespace Congress\Lib\Helpers;

final class URLGenerator
{
	private function __construct() { }
	
	public static bool $useFriendlyUrls = false;
	
	public static function generatePageUrl(string $pagePath, array $query = []) : string
	{
		$qs = count($query) > 0 ? (self::$useFriendlyUrls ? '?' : '&') . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => $pagePath[0] == '/' ? $pagePath . $qs : '/' . $pagePath . $qs,
			false => "index.php?page=$pagePath$qs"
		};
	}
	
	public static function generateFileUrl(string $filePathFromRoot, array $query = []) : string
	{
		$qs = count($query) > 0 ? (self::$useFriendlyUrls ? '?' : '&') . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => "/--file/$filePathFromRoot" . $qs,
			false => $filePathFromRoot[0] == '/' ? mb_substr($filePathFromRoot, 1) . $qs : $filePathFromRoot . $qs
		};
	}
	
	public static function generateScriptUrl(string $filePathFromScriptDir, array $query = []) : string
	{
		$qs = count($query) > 0 ? (self::$useFriendlyUrls ? '?' : '&') . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => "/--script/$filePathFromScriptDir" . $qs,
			false => $filePathFromScriptDir[0] == '/' ? "script$filePathFromScriptDir" . $qs : "script/$filePathFromScriptDir" . $qs
		};
	}

	private static function generateQueryString(array $queryData) : string
	{
		return http_build_query($queryData);
	}
}