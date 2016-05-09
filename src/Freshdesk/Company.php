<?php

namespace Freshdesk;

use Freshdesk\Model\Company as CompanyM;

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

    public function addCompany(CompanyM $data)
    {
        $url = "/api/v2/companies";
        $paramsData = $this->toJsonData();

        $response = json_decode(
            $this->restCall(
                $url,
                Rest::METHOD_POST,
                $paramsData
            )
        );

        return $data->setAll($response);
    }

    public function getCompany($id)
    {
        $url = "/api/v2/companies/{$id}";

        $response = json_decode(
            $this->restCall(
                $url,
                Rest::METHOD_GET
            )
        );

        $model = new CompanyM();

        return $model->setAll($response);
    }

}