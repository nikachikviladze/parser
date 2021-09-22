<?php

namespace App\Repository;
use Response;
use Helper;

class XmlExportRepository{

    public static function dataExport($results)
    {

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mydoc></mydoc>');
        $xml->addAttribute('version', '1.0');
        $xml->addChild('datetime', date('Y-m-d H:i:s'));
        
        foreach($results as $result ){
            $content = $xml->addChild('content');
            $content->addChild('title', $result->post_title);
            $content->addChild('description', htmlentities(Helper::stringParser($result->post_content), ENT_XML1));
        }
        $xml->saveXML('data.xml');
    
        $response = Response::make($xml->asXML(), 200);
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=data.xml');
        $response->header('Content-Transfer-Encoding', 'binary');
        $response->header('Content-Type', 'text/xml');
        
        return $response;
    }
}
