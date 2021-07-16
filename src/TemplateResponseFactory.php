<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Framework\Exception\FrameworkException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TemplateResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    /**
     * @var array
     */
    private array $default_contexts = [];

    /**
     * @var array
     */
    private array $contexts = [];

    /**
     * @param ServerRequestInterface $request
     * @param array $default_contexts
     */
    public function __construct(ServerRequestInterface $request, $default_contexts = [])
    {
        $this->request = $request;
        $this->default_contexts = $default_contexts;
    }

    /**
     * @param string|Route $path
     * @param array $contexts
     * @return self
     */
    public function setContexts(array $contexts = []): self
    {
        $this->contexts = $contexts;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateFilePath(): string
    {
        $route = $this->request->getAttribute("route");
        $template_file = str_replace(["-", "_"], "", strtolower($route->getActionName())) . ".html";
        return dirname(__DIR__) . "/template/" . $route->getModuleName() . "/" . $template_file;
    }

    /**
     * @return string
     */
    public function createContents(): string
    {
        $template_filepath = $this->getTemplateFilePath();
        $contents = file_get_contents($template_filepath);

        if ($contents === false) {
            throw new FrameworkException("Failed to get the template file contents.");
        }

        return static::assignContexts($contents, array_merge($this->contexts, $this->default_contexts));
    }

    /**
     * @param integer $code
     * @param string $reasonPhrase
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $psr17_factory = new Psr17Factory();
        $body = $psr17_factory->createStream($this->createContents());
        $response = $psr17_factory->createResponse($code, $reasonPhrase);
        $response = $response->withBody($body);

        return $response;
    }

    /**
     * @param string $text
     * @param array $contexts
     * @return string|null
     */
    static public function assignContexts(string $text, array $contexts): ?string
    {
        $match_strs = [];
        preg_match_all("/\\" . "___" . ".+?\\" . "___" . "/", $text, $match_strs);

        foreach ($match_strs[0] as $match_str) {
            $keyword = str_replace("___", "", $match_str);

            if ($keyword == "") {
                continue;
            }
            if (array_key_exists($keyword, $contexts)) {
                $text = str_replace([$match_str], $contexts[$keyword], $text);
            } else {
                $text = str_replace([$match_str], "", $text);
            }
        }
        return $text;
    }
}
