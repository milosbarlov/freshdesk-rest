<?php

namespace Freshdesk;

use Freshdesk\Model\Contact as ContactM;
class Contact extends Rest
{

    /**
     * @param $id
     * @param ContactM $model
     * @return \Freshdesk\Model\Contact
     * @throws \RuntimeException
     */
    public function getContactById($id, ContactM $model = null)
    {
        $response = json_decode(
            $this->restCall(
                "/api/v2/contacts/{$id}",
                Rest::METHOD_GET
            )
        );
        if (property_exists($response, 'errors'))
            throw new \RuntimeException(
                sprintf('Error: %s', $response->errors->error)
            );
        if ($model === null)
            $model = new ContactM();
        return $model->setAll(
            $response
        );
    }
    
    public function getAllContactByCompany($companyId)
    {
        $url = "/api/v2/contacts?company_id={$companyId}";

        $response = json_decode(
            $this->restCall(
                $url,
                Rest::METHOD_GET
            )
        );

       return $response;
    }

    public function setUser(ContactM $contact){
        $data = $contact->toJsonData();

        $response = $this->restCall(
            '/api/v2/contacts',
            self::METHOD_POST,
            $data
        );
        if (!$response)
            throw new RuntimeException(
                sprintf(
                    'Failed to create ticket with data: %s',
                    $data
                )
            );
        $json = json_decode($response);

        return $contact->setAll($json);
    }
}