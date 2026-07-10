<?php

namespace App\Http\Controllers;

use App\Models\BrochureDetails;
use App\Models\BrochureLanguage;
use App\Models\BrochureMaster;
use App\Models\BrochureProduct;
use App\Models\BrochureProductDetails;
use App\Models\CmsLinks;
use App\Models\Distributor\DistributorContents;
use App\Models\Media\ImageCategories;
use App\Models\Menu\MenuMaster;
use App\Models\Menu\NaviMaster;
use App\Models\Referral;
use App\Models\Webinar;
use App\Models\WebinarUser;
use App\Services\MicrosoftGraphMailService;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Mail;
use Redirect;
use Session;
use View;

class SitemapController extends Controller
{

    public function __construct(Request $request)
    {

        $requestURL = trim($request->url());
        //echo $requestURL;
        //die;
        $ckRed = DB::table('redirection')->where('type', '=', '301')->where('status', '=', '1')
            ->where('source_url', '=', $requestURL)->first();

        if (!empty($ckRed) && $ckRed->destination_url != '') {
            return Redirect::to($ckRed->destination_url, 301)->send();
        }

        $currlngid = '1';
        $currlngcode = 'en';

        if (Session::has('current_lng')) {
            $currlngid = Session::get('current_lng');
            $currlngcode = Session::get('current_lngcode');
        }

        $shareData = array();

        $shareData['currlngid'] = $currlngid;
        $shareData['currlngcode'] = $currlngcode;

        $mainMenu = NaviMaster::where('menu_id', '=', '2')->where('parent_page_id', '=', '0')
            ->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['mainMenu'] = $mainMenu;
   
        $stickyFooter = NaviMaster::where('menu_id', '=', '4')->where('parent_page_id', '=', '0')
            ->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['stickyFooter'] = $stickyFooter;

        $footerMenu = NaviMaster::where('menu_id', '=', '3')->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['footerMenu'] = $footerMenu;

        $socialLinks = \App\Models\SocialLinks::where('status', '=', '1')->orderBy('display_order', 'asc')->get();
        $shareData['socialLinks'] = $socialLinks;

        View::share($shareData);
    }
   

     public function update()
{
 
    $file = public_path('sitemap-multotec.xml');

    if (!file_exists($file)) {
        return response('sitemap-multotec.xml not found.', 404);
    }

    $xml = simplexml_load_file($file);

    $existing = [];

    foreach ($xml->url as $url) {
        $existing[] = (string) $url->loc;
    }

    $newUrlsAdded = 0;

    /*
    |--------------------------------------------------------------------------
    | News Articles
    |--------------------------------------------------------------------------
    */

    $articles = DB::table('articles')
        ->where('status', 1)
        ->where('parent_language_id', 0)
        ->get();

    foreach ($articles as $article) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/news-articles/' . $article->slug)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Brochures
    |--------------------------------------------------------------------------
    */

    $brochures = DB::table('brochure_master')
        ->where('status', 1)
        ->get();

    foreach ($brochures as $brochure) {

        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/brochure/' . $brochure->slug)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Image Gallery
    |--------------------------------------------------------------------------
    */

    $categories = DB::table('image_category')
        ->where('parent_category_id', 0)
        ->where('status', 1)
        ->where('show_in_gallery', 1)
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->orderBy('display_order')
        ->get();

    foreach ($categories as $category) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/gallery/images/' . ltrim($category->slug, '/'))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Webinars
    |--------------------------------------------------------------------------
    */

    $webinars = DB::table('webinar')
        ->where('status', 1)
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->get();

    foreach ($webinars as $webinar) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/webinar/' . $webinar->slug)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    $events = DB::table('event_management')
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->get();

    foreach ($events as $event) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/event-details/' . $event->slug)
        );
    }






       /*
    |--------------------------------------------------------------------------
    | People Profile
    |--------------------------------------------------------------------------
    */

    $peoples = DB::table('peoples_profile')
        ->where('status', '=', '1')
        ->get();

    foreach ($peoples as $people) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/profiles/' . $people->slug)
        );
    }


    
       /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    $newproducts = DB::table('products')
        ->where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->where('is_duplicate', '=', '0')->where('parent_id', '=', '0')->orderBy('id', 'desc')
        ->get();

    foreach ($newproducts as $newp) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/' . $newp->slug)
        );
    }

    


         
       /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    $newflow = DB::table('flowsheet')
        ->where('status', '!=', '3')->where('parent_language_id', '=', '0')->where('parent_id', '=', '0')
        ->orderBy('created_at', 'desc')
        ->get();

    foreach ($newflow as $newflowvalue) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/' . $newflowvalue->slug)
        );
    }

     /*
    |--------------------------------------------------------------------------
    | Landing  Page
    |--------------------------------------------------------------------------
    */

    $landing = DB::table('landing_pages')
         ->orderBy('id', 'desc')->get();
 
    foreach ($landing as $lval) {
        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/landing-pages/' . $lval->slug)
        );
    }


    //image categories

    
    $csub = ImageCategories::with(['parent'])->where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
 
foreach ($csub as $cat) {

    if ($cat->parent) {

        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/gallery/images/' . ltrim($cat->parent->slug, '/') . '/' . ltrim($cat->slug, '/'))
        );

    } else {

        $newUrlsAdded += $this->appendUrl(
            $xml,
            $existing,
            url('en/gallery/images/' . ltrim($cat->slug, '/'))
        );
    }
}




    // $xml->asXML($file);

    $dom = new \DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dom->loadXML($xml->asXML());

$dom->save($file);

    $destination = base_path('sitemap.xml');
    copy($file, $destination);

    return response()->json([
        'success' => true,
        'message' => 'Sitemap updated successfully.',
        'new_urls_added' => $newUrlsAdded,
        'total_urls' => count($existing),
    ]);
}

/**
 * Append URL if it doesn't already exist.
 */
private function appendUrl(&$xml, array &$existing, string $loc): int
{
    if (in_array($loc, $existing, true)) {
        return 0;
    }

    $url = $xml->addChild('url');
    $url->addChild('loc', $loc);

    $existing[] = $loc;

    return 1;
}

}
