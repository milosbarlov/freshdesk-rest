<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 6.5.16.
 * Time: 11.40
 */

namespace Freshdesk;
use Freshdesk\Model\Product as ProductM;


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

        if(!$response)
            return null;

        $models = [];
        foreach($response as $model){
            $models[] = new ProductM($model);
        }

        return $models;
    }

    public function getProduct($id)
    {
        $url = "/api/v2/products/{$id}";

        $response = json_decode(
            $this->restCall(
             $url,
             Rest::METHOD_GET
            )
        );

        $model = new ProductM();

        return $model->setAll($response);
    }

}