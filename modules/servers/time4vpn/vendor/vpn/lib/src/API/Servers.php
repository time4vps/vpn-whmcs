<?php

namespace Time4VPN\API;

use Time4VPN\Base\Endpoint;
use Time4VPN\Exceptions\APIException;
use Time4VPN\Exceptions\AuthException;
use Time4VPN\Exceptions\Exception;

class Servers extends Endpoint
{
    /**
     * Servers constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct('server');
    }

    /**
     * Get all servers
     * @return array Available servers array
     * @throws APIException|AuthException
     */
    public function all()
    {
        return $this->get();
    }

}