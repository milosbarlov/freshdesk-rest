<?php

namespace Freshdesk;


class Company extends Rest
{

    public function getAllCompany()
    {
        $url = "/api/v2/companies";

        $response = json_decode(
            $this->restCall(
                $url,
                Rest::METHOD_GET
            )
        );

        return $response;
    }

}