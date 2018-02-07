<?php

$methods = [
    'getServerParams()',
    'getCookieParams()',
    'withCookieParams(array $cookies)',
    'getQueryParams()',
    'withQueryParams(array $query)',
    'getUploadedFiles()',
    'withUploadedFiles(array $uploadedFiles)',
    'getParsedBody()',
    'withParsedBody($data)',
    'getAttributes()',
    'getAttribute($name, $default = null)',
    'withAttribute($name, $value)',
    'withoutAttribute($name)',
    'getRequestTarget()',
    'withRequestTarget($requestTarget)',
    'getMethod()',
    'withMethod($method)',
    'getUri()',
    'withUri(UriInterface $uri, $preserveHost = false)',
    'getProtocolVersion()',
    'withProtocolVersion($version)',
    'getHeaders()',
    'hasHeader($name)',
    'getHeader($name)',
    'getHeaderLine($name)',
    'withHeader($name, $value)',
    'withAddedHeader($name, $value)',
    'withoutHeader($name)',
    'getBody()',
    'withBody(StreamInterface $body)',
];

$text = '%s'."\n";

echo "\n\n";

$offsetY = 350;
$i = 0;
foreach ($methods as $method) {
    
    if (($i % 3) == 0 && ($i !== 0)) {
        printf("</text>\n");
    }
    if (($i % 3) == 0) {
        printf(
            '<text x="250" y="%d" fill="black" font-size="18">'."\n",
            $offsetY
        );
    }
    
    echo "    ".$method."\n";
    
    $offsetY += 10;

    $i++;
}


if (!($i % 3)) {
        printf("</text>\n");
}