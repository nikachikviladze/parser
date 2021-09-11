<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;
use Storage;

class PagesController extends Controller
{
    public function index(){

        $files = Storage::files('public/DB');

        return view('welcome', ['files'=>$files]);
    }
    public function download(Request $request){

        $this->validate($request, ['type'=>'required|string|in:xml,csv,txt', 'db'=>'required|array']);

        $db1=collect();
        $db2=collect();
        $db3=collect();
        $db4=collect();

        if(in_array('db1', $request->db)){
            $db1 = DB::connection('mysql')->table('wp1of20_posts')->select('post_title', 'post_content')->get();
        }
        if(in_array('db2', $request->db)){
            $db2 = DB::connection('mysql2')->table('wp_posts')->select('post_title', 'post_content')->get();
        }
        if(in_array('db3', $request->db)){
            $db3 = DB::connection('mysql3')->table('wp1of20_posts')->select('post_title', 'post_content')->get();
        }
        if(in_array('db4', $request->db)){
            $db4 = DB::connection('mysql4')->table('wp_posts')->select('post_title', 'post_content')->get();
        }

        $results = $db1->merge($db2)->merge($db3)->merge($db4);

        function string_parser($string){
            $x = preg_split('/\r\n|\r|\n/', strip_tags($string));
            $x= implode(' ', $x);
            $desc = preg_replace('/[\x00-\x1F\x7F]/', '', $x);
            return $desc;
        }

        // txt export
        if($request->type=='txt'){
            $text='';
            foreach($results as $key=>$result){
                $text .= $result->post_title. PHP_EOL . PHP_EOL . string_parser($result->post_content) .PHP_EOL .'____________'. PHP_EOL;
            }
            $myName = "data.txt";
            $headers = ['Content-type'=>'text/plain', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $myName),'X-BooYAH'=>'WorkyWorky'];
            return Response::make($text, 200, $headers);
        }
        // xml export
        if($request->type=='xml'){
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mydoc></mydoc>');
            $xml->addAttribute('version', '1.0');
            $xml->addChild('datetime', date('Y-m-d H:i:s'));
            
            foreach($results as $result ){
                $content = $xml->addChild('content');
                $content->addChild('title', $result->post_title);
                $content->addChild('description', htmlentities(string_parser($result->post_content), ENT_XML1));
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
        // csv export
        if($request->type=='csv'){
            return Excel::download(new DataExport($results), 'data.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }
}
