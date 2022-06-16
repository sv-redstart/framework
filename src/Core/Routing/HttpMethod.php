<?php

namespace Core\Routing;

enum HttpMethod: string
{
    case GET    = 'GET';
    case POST   = 'POST';
    case PUT    = 'PUT';
    case PACH   = 'PATCH';
    case DELETE = 'DELETE';
}
