<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheKernel extends HttpCache
{
    protected function invalidate(Request $request, $catch = false)
    {
        $response = parent::invalidate($request, $catch);

        // invalidate only when the response is successful
        if ($request->getMethod() == "DELETE") {
                $uri = $request->getRequestURI();

                $explode = explode("/", $uri);
                $lastPart = $explode[count($explode)-1];

                // Check if $lastPart is an integer
            if (ctype_digit($lastPart)) {
                $uri = str_replace("/".$lastPart, "", $uri);

                $subRequest = Request::create($uri, 'get', array(), array(), array(), $request->server->all());

                $this->getStore()->invalidate($subRequest);
            }
        }

        return $response;
    }
}
