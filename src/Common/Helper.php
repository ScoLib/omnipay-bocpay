<?php

namespace Omnipay\BOCPay\Common;

class Helper
{
    public static function array2xml($arr, $root = 'xml')
    {
        $xml = "<$root>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";

        return $xml;
    }


    public static function xml2array($xml)
    {
        libxml_disable_entity_loader(true);

        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        if (! is_array($data)) {
            $data = [];
        }

        return $data;
    }
}
