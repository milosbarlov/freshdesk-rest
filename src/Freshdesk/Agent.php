<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 9.5.16.
 * Time: 14.55
 */

namespace Freshdesk;

use Freshdesk\Model\Agent as AgentM;

class Agent extends Rest
{
    public function getAgent($id)
    {
        $agent = new AgentM();

        $url = "/api/v2/agents/{$id}";

        $response = json_decode(
            $this->restCall(
                $url,
                self::METHOD_GET
            )
        );

        return $agent->setAll($response);
    }

    public function getAllAgents()
    {
        $url = "/api/v2/agents";

        $response = json_decode(
            $this->restCall(
                $url,
                self::METHOD_GET
            )
        );

        $models = [];
        foreach($response as $res){
            $models[] = new AgentM($res);
        }
        
        return $models;
    }

    public function getAllGroups()
    {
        $url="/api/v2/groups";

        $response = json_decode(
            $this->restCall(
                $url,
                self::METHOD_GET
            )
        );

        return $response;
    }
}