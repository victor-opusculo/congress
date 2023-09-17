<?php
namespace PComp;

require_once "HtmlTag.php";
require_once "HtmlSelfClosingTag.php";
require_once "Component.php";
require_once "Text.php";

final class View
{
    private function __construct() { }

    public static function tag(string $tagName, ...$properties) : Component
    {
        return new HtmlTag($tagName, ...$properties);
    }

    public static function scTag(string $tagName, ...$properties) : Component
    {
        return new HtmlSelfClosingTag($tagName, ...$properties);
    }

    public static function render(array $components) : void
    {
        foreach ($components as $comp)
        {
            if ($comp instanceof Component)
                $comp->render();
            else if (is_array($comp))
                self::render($comp);
        }
    }

    public static function text(string $string) : Component
    {
        return new Text($string);
    }

    public static function rawText(string $string) : Component
    {
        return new Text($string, false);
    }

    public static function component(string $componentClassName, ...$properties) : Component
    {
        return new $componentClassName(...$properties);
    }
}