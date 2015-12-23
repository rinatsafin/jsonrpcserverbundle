<?php

namespace TSCore\JsonRpcServerBundle\Parser;

use TSCore\JsonRpcServerBundle\Parser\Exception\ParseException;

class JsonParser implements IParser
{
    /**
     * @param string $rawJson
     * @return array()
     * @throws ParseException
    */
    public function parse($rawJson)
    {
        $rawJson = (string)$rawJson;

        $result = @json_decode($rawJson, true);

        if (is_null($result))
            throw new ParseException("Can't parse json. " . json_last_error_msg(), json_last_error(), $rawJson);

        return $result;
    }
}