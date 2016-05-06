<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 6.5.16.
 * Time: 11.40
 */

namespace Freshdesk;

class Product extends Rest
{

    public function getAllProduct()
    {
        $url = "/api/v2/products";

        $response = json_decode(
            $this->restCall(
                $url,
                Rest::METHOD_GET
            )
        );

        return $response;
    }

}