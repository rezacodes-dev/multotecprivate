<?php

namespace App\Http\Controllers;

use App\Models\BrochureDetails;
use App\Models\BrochureLanguage;
use App\Models\BrochureMaster;
use App\Models\BrochureProduct;
use App\Models\BrochureProductDetails;
use App\Models\CmsLinks;
use App\Models\Distributor\DistributorContents;
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
use Jenssegers\Agent\Agent;
use Mail;
use Redirect;
use Session;
use View;

class FrontEndController extends Controller
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


public function extractBranchContacts()
{
    DB::table('branch_temp_contact')->truncate();

    $contents = DB::table('distributor_contents')
        ->select('id', 'page_content')
        ->get();

    foreach ($contents as $dc) {

        $html = html_entity_decode(html_entity_decode($dc->page_content ?? ''));

        $addressContent = '';

        if (preg_match('/<strong>\s*Address:\s*<\/strong>(.*?)<\/p>/is', $html, $addressMatch)) {
            $addressContent .= '<p><strong>Address:</strong>' . trim($addressMatch[1]) . '</p>';
        }

        if (preg_match('/<strong>\s*Tel:?\s*<\/strong>(.*?)<\/p>/is', $html, $telMatch)) {
            $addressContent .= '<p><strong>Tel:</strong>' . trim($telMatch[1]) . '</p>';
        }

        if (preg_match('/<strong>\s*Email:?\s*<\/strong>(.*?)<\/p>/is', $html, $emailMatch)) {
            $addressContent .= '<p><strong>Email:</strong>' . trim($emailMatch[1]) . '</p>';
        }

        DB::table('branch_temp_contact')->insert([
            'branch_id'       => $dc->id,
            'address_content' => $addressContent,
        ]);
    }

    return 'Done';
}


    public function saveWbUser(Request $request)
    {

        $webinar_id = trim(ucfirst($request->input('webinar_id')));

        $email_id = trim(ucfirst($request->input('email_id')));

        $exdata = WebinarUser::where('webinar_id', $webinar_id)->where('email_id', $email_id)->get();

        $data = Webinar::with(['WebinarCategory'])->where('id', $webinar_id)->first();
        //$query = \App\Models\Webinar::where('name', $webinar_id)->where('status', '=', '1')->first();
        //$query = \App\Models\Webinar::where('slug', $id);
        $slug = 'https://www.multotec.com/en/webinar-content/' . $data->slug;
        $pname = $data->name;

        // $data1 = Webinar::with(['WebinarCategory'])->sum('conversion');
        // $conversions = $data1;
        //$hit = $data->hit;
        //$conversion = $data->conversion;
        //dd($data);
        //dd($data['attributes']['name']);


        $webinar_url = trim(ucfirst($request->input('webinar_url')));

        if (count($exdata) == 0) {

            $Webinar = new WebinarUser;
            $Webinar->name = trim(ucfirst($request->input('name')));
            $Webinar->contact_no = trim(ucfirst($request->input('contact_no')));
            $Webinar->email_id = trim(ucfirst($request->input('email_id')));
            $Webinar->webinar_id = trim(ucfirst($request->input('webinar_id')));

            $Webinar->company = trim(ucfirst($request->input('company')));
            $Webinar->country = trim(ucfirst($request->input('country')));
            //$Webinar->webinar_url = trim( ucfirst($request->input('webinar_url')) );

            $resx = $Webinar->save();

            //conversion
            $webinar_id = $Webinar->webinar_id;

            $conversion_has = Session::get('conversion_has' . $webinar_id);

            if (isset($conversion_has) && $conversion_has == $webinar_id) {
            } else {
                $SWebinar = \App\Models\Webinar::find($webinar_id);

                $conversion = $SWebinar->conversion;
                $conversion = $conversion + 1;
                $SWebinar->conversion = $conversion;

                $SWebinar->save();

                session(['conversion_has_' . $webinar_id => $webinar_id]);
            }

            $data1 = Webinar::with(['WebinarCategory'])->sum('conversion');
            $conversions = $data1;

            // $webinar_email = $Webinar->email_id; 

            // $SWebinar = \App\Models\Webinar::find($webinar_id); 

            // $conversion = $SWebinar->conversion; 
            // $conversion = $conversion + 1; 
            // $SWebinar->conversion = $conversion;                

            // $SWebinar->save(); 


            //conversion

            if (isset($resx) && $resx == 1) {

                $name = trim(ucfirst($request->input('name')));
                $contact_no = trim(ucfirst($request->input('contact_no')));
                $email_id = trim(ucfirst($request->input('email_id')));
                $webinar_id = trim(ucfirst($request->input('webinar_id')));

                $company = trim(ucfirst($request->input('company')));
                $country = trim(ucfirst($request->input('country')));

                $webinar_url = trim(ucfirst($request->input('webinar_url')));



                $mailBODY = '';
                $mailBODY .= 'Name = ' . $name . '<br/>';
                $mailBODY .= 'Contact No = ' . $contact_no . '<br/>';
                $mailBODY .= 'Email Id = ' . $email_id . '<br/>';
                $mailBODY .= 'Webinar Requested = ' . $pname . '<br/>';
                $mailBODY .= 'Webinar Page Link = ' . $slug . '<br/>';

                //$mailBODY .= 'Total Hits = '.$hit.'<br/>';
                $mailBODY .= 'Watch Request No. = ' . $conversions . '<br/>';



                if ($company != '') {
                    $mailBODY .= 'Company = ' . $company . '<br/>';
                }

                $mailBODY .= 'Country = ' . $country . '<br/>';

                $mail_sub = "New Multotec Webinar Watch Request - " . $conversions;

                $empTemp = \App\Models\EmailTemplate::find(3);


                if (!empty($empTemp)) {

                    $content = $empTemp->description;
                    $mailBODY = str_replace("[ENQ_CONTENT]", $mailBODY, $content);
                }


                $emailData = array();
                $emailData['subject'] = $mail_sub;
                $emailData['body'] = trim($mailBODY);
                $emailData['from_name'] = "Multotec";
                $emailData['from_email'] = "support@multotec.com";
                $mailArr = array("heathl@cubicice.com", "tarryn@cubicice.com", "marketing@multotec.com", "multotecwebenquiry@cubicice.com");
                foreach ($mailArr as $vem) {
                    // $emailData = array();
                    // $emailData['subject'] = $mail_sub . ' '. $rerf_url;
                    // $emailData['body'] = trim($mailBODY);
                    $emailData['to_email'] = trim($vem);
                    // $emailData['from_email'] = env('MAIL_FROM_ADDRESS',"support@multotec.com");
                    // $emailData['from_name'] = "Multotec";
                    try {
                        Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

                            // $message->from($emailData['from_email'], $emailData['from_name']);

                            $message->to($emailData['to_email'])->subject($emailData['subject']);
                        });
                    } catch (\Exception $e) {
                        // Do nothing 
                    }
                }

                //$emailData['to_email'] = trim('dipanwitachanda1991@gmail.com'); 
                //  $emailData['to_email'] = trim('zeeshan.mymail@gmail.com');

                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });

                // $emailData['to_email'] = trim('koenal@multotec.com');  
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });



                // $emailData['to_email'] = trim('viviennem@multotec.com'); 
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });



                // $emailData['to_email'] = trim('heathl@cubicice.com'); 
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });


                // $emailData['to_email'] = trim('tarryn@cubicice.com'); 
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });

                // $emailData['to_email'] = trim('multotecwebenquiry@cubicice.com'); 
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     // $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });

                // $emailData['to_email'] = trim('annahv@multotec.com'); 
                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                //     $message->from($emailData['from_email'], $emailData['from_name']); 
                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });


                return redirect($webinar_url);
            }
        } else {
            return redirect($webinar_url);
        }


        return back()->with('msg', 'Something Went Wrong')
            ->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinar($category_id)
    {
        $ck = Webinar::find($category_id);
        if (isset($ck) && !empty($ck)) {
            $ck->status = '3';
            $res = $ck->save();
            if (isset($res) && $res == 1) {

                return back()->with('msg', 'Webinar Deleted Successfully.')
                    ->with('msg_class', 'alert alert-success');
            }
        }

        return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function home(Request $request, $lng = '')
    {

       

        // if (isset($_SERVER['HTTP_REFERER'])) {


        //     $r = explode('/', $_SERVER['HTTP_REFERER']);


        //     if (isset($r[2])) {

        //         $pattern = '/https/i';
        //         $r[2] = preg_replace($pattern, '', $r[2]);

        //         $pattern = '/http/i';
        //         $r[2] = preg_replace($pattern, '', $r[2]);

        //         $pattern = '/www./i';
        //         $r[2] = preg_replace($pattern, '', $r[2]);


        //         $str = $_SERVER['HTTP_REFERER'];
        //         $pattern = "/[?]/i";
        //         $flag = preg_match($pattern, $str);


        //         if ($flag == 1 && $r[2] == 'multotec.com') {


        //             Session::put('referral', $_SERVER['HTTP_REFERER']);
        //             setcookie('dipa', $_SERVER['HTTP_REFERER'], time() + (86400 * 30), "/");

        //             $CmsLinks = new Referral;
        //             $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
        //             $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
        //             $CmsLinks->save();
        //         } else if ($r[2] != 'multotec.com') {

        //             Session::put('referral', $_SERVER['HTTP_REFERER']);
        //             setcookie('dipa', $_SERVER['HTTP_REFERER'], time() + (86400 * 30), "/");

        //             $CmsLinks = new Referral;
        //             $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
        //             $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
        //             $CmsLinks->save();
        //         }
        //         // Session::save();

        //     }
        // }

        // if(isset($_SERVER['HTTP_REFERER'])  && $_SERVER['HTTP_REFERER']!='https://www.multotec.com/'  && $_SERVER['HTTP_REFERER']!='https://multotec.com/' && $_SERVER['HTTP_REFERER']!='http://multotec.com/' && $_SERVER['HTTP_REFERER']!='http://multotec.com'  && $_SERVER['HTTP_REFERER']!='https://multotec.com' && $_SERVER['HTTP_REFERER']!='http://www.multotec.com/'  && $_SERVER['HTTP_REFERER']!='https://www.multotec.com/en' && $_SERVER['HTTP_REFERER']!='http://www.multotec.com/en'){

        //     $CmsLinks = new Referral;
        // 	$CmsLinks->referral = $_SERVER['HTTP_REFERER'];
        // 	$CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
        // 	$CmsLinks->save();

        // }


        $DataBag = array();

        $currlngcode = $lng;

        if ($lng == '' && $lng == NULL) {
            $lng = 'en';
        }

        $currlngid = 1;

        if ($lng == 'esl') {
            $currlngid = '5';
        } else if ($lng == 'en') {
            $currlngid = '1';
        } else if ($lng == 'por') {
            $currlngid = '6';
        } else if ($lng == 'ca') {
            $currlngid = '7';
        }

        // if(Session::has('current_lng') && $currlngid) {
        //     $currlngid = Session::get('current_lng');
        //     $currlngcode = Session::get('current_lngcode');
        // } 
        // else{
        Session::put('current_lng', $currlngid);
        Session::put('current_lngcode', $currlngcode);
        // }

        if ($lng == '' && $lng == NULL && count($request->segments()) == 0) {

            $forceURL = $request->url() . '/' . $currlngcode;
            return redirect($forceURL);
        }
        $DataBag['lng'] = $lng;
        // $getlngid = getLngIDbyCode($lng);
        $getlngid = 1;

        $device = 1;
        // $agent = new Agent();
        // if ($agent->isMobile()) {
        //     $device = 2;
        // }
        $DataBag['device'] = $device;

        if($DataBag['device'] == 1){
            $DataBag['home_banners'] = \App\Models\Banners::with(['BannerImages'])->get();
           }
 

    $DataBag['logos'] = DB::select("
    SELECT *
    FROM home_logo
    WHERE status = 1
    ORDER BY display_order ASC
");

$data = \App\Models\HomeContent::where('language_id', $currlngid)->first();
$news_limit = $data->news_no;

         $DataBag['mps'] = \App\Models\MineralProcess::where('status', '=', '1')->orderBy('display_order', 'asc')->get();

// $DataBag['mps'] = DB::select("
//     SELECT *
//     FROM mineral_processing
//     WHERE status = 1
//     ORDER BY display_order ASC
// ");

$DataBag['minerals'] = DB::select("
    SELECT *
    FROM mineral
    WHERE status = 1
    ORDER BY display_order ASC
");


$DataBag['news'] = DB::select("
    SELECT * FROM articles
    WHERE status = 1 AND language_id = ?
    ORDER BY publish_date DESC
    LIMIT ?
", [$getlngid, $news_limit]);

$DataBag['map'] = \App\Models\HomeMap::first();

        $DataBag['allData'] = $data;
        $DataBag['page_metadata'] = $DataBag['allData'];


    
        $DataBag['commodities'] = DB::table('commodities_pricing')->where('status', 1)
            // ->where('id',3)
            ->get();
        return view('front_end.home', $DataBag);
    }


    /********************************************************************************************************************/
    public function cmsPage($lng, $slug)
    {
            
       

        if (isset($_SERVER['HTTP_REFERER'])) {


            $r = explode('/', $_SERVER['HTTP_REFERER']);


            if (isset($r[2])) {

                $pattern = '/https/i';
                $r[2] = preg_replace($pattern, '', $r[2]);

                $pattern = '/http/i';
                $r[2] = preg_replace($pattern, '', $r[2]);

                $pattern = '/www./i';
                $r[2] = preg_replace($pattern, '', $r[2]);



                $str = $_SERVER['HTTP_REFERER'];
                $pattern = "/[?]/i";
                $flag = preg_match($pattern, $str);



                // dd($flag);

                if ($flag == 1 && $r[2] == 'multotec.com') {


                    Session::put('referral', $_SERVER['HTTP_REFERER']);
                    setcookie('dipa', $_SERVER['HTTP_REFERER'], 3600);

                    $CmsLinks = new Referral;
                    $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
                    $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
                    $CmsLinks->save();
                } else if ($r[2] != 'multotec.com') {
                    Session::put('referral', $_SERVER['HTTP_REFERER']);
                    setcookie('dipa', $_SERVER['HTTP_REFERER'], 3600);
                    $CmsLinks = new Referral;
                    $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
                    $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
                    $CmsLinks->save();
                }
            }
        }

        Session::get('referral');
        // $lng = Session::get('current_lng');

        // if($lng==0 || $lng==''){
        //     $lng=1;
        // }

        $DataBag = array();
        $breadcrumbs = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        // $getlngid = getLngIDbyCode( $lng );
        $getlngid = $lng;
        $view = 'front_end.home';

        $cms = CmsLinks::where('slug_url', '=', trim($slug))->first();
       
        if (!empty($cms)) {
          //    dd($cms);
            $table_id = $cms->table_id;
            $table_type = $cms->table_type;
        
            /** PRODUCT **/
            if ($table_type == 'PRODUCT') {

                $data = \App\Models\Product\Products::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status', 1)->first();

                if (empty($data)) {
                    abort(404);
                }

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.product.product_page';
            }

            /** PRODUCT CATEGORY **/
            if ($table_type == 'PRODUCT_CATEGORY') {

                $data = \App\Models\Product\ProductCategories::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status', 1)->first();

                if (empty($data)) {
                    abort(404);
                }

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.product.product_category_page';
            }

            /** TECHNICAL RESOURCE **/
            if ($table_type == 'TECH_RESOURCE') {

                $data = \App\Models\TechResource\TechResource::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->first();

                    if (empty($data)) {
                        abort(404);
                    }

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.tech_resource.tech_resource_content';
            }

            /** CONTENTS **/
            if ($table_type == 'DYNA_CONTENT') {
                    
                $data = \App\Models\Content\Contents::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status', 1)->first();

                if (empty($data)) {
                    abort(404);
                }


                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.dyna_content.content_page';
            }

            /** ARTICLE **/
            if ($table_type == 'ARTICLE') {

                return redirect()->route('front.artCont', array('lng' => $lng, 'slug' => $slug));
            }


            /** ARTICLE CATEGORY **/
            if ($table_type == 'ARTICLE_CATEGORY') {

                return redirect()->route('newsArticleList', array('lng' => $lng));
            }


            /** EVENT **/
            if ($table_type == 'EVENT') {

                $data = \App\Models\Events::with(['pageBuilderContent'])
                    ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $eventCats = \App\Models\EventCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->paginate(20);
                $DataBag['listCats'] = $eventCats;

                $yearArr = array();
                $createdAt = \App\Models\Events::where('status', '=', '1')
                    ->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->pluck('created_at')->toArray();
                if (!empty($createdAt)) {
                    foreach ($createdAt as $v) {
                        $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                        array_push($yearArr, $onlyYear);
                    }
                }
                $uniqueYear = array_unique($yearArr);
                $DataBag['yearList'] = $uniqueYear;

                $view = 'front_end.event.content_page';
            }

            /** EVENT CATEGORY **/
            if ($table_type == 'EVENT_CATEGORY') {

                return redirect()->route('eventLists', array('lng' => $lng));
            }

            if ($table_type == 'EVENT_MANAGEMENT') {

                return redirect()->route('currentEvents', array('year' => date('Y')));
            }

            /** CONTENTS **/


            /** INDUSTRY **/
            if ($table_type == 'INDUSTRY') {

                $data = \App\Models\Industry\Industries::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status', 1)->first();
                    if (empty($data)) {
                        abort(404);
                    }
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                $DataBag['commodities'] = DB::table('commodities_pricing')->where('status', 1)->get();

                $view = 'front_end.industry.industry';
            }



            /** FLOWSHEET CATEGORY **/
            if ($table_type == 'FLOWSHEET_CATEGORY') {

                $data = \App\Models\IndustryFlowsheet\FlowsheetCategories::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status', 1)->first();
                    if (empty($data)) {
                        abort(404);
                    }
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.industry.flowsheet_category';
            }




            /** FLOWSHEET **/
            if ($table_type == 'FLOWSHEET') {

                $data = \App\Models\IndustryFlowsheet\Flowsheet::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status',1)->first();
                    if (empty($data)) {
                        abort(404);
                    }

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['fsmarkers'] = \App\Models\IndustryFlowsheet\FlowsheetMarker::where('flowsheet_id', '=', $table_id)->get();



                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.industry.flowsheet';
            }




            /** DISTRIBUTOR CATEGORY **/
            if ($table_type == 'DISTRIBUTOR_CATEGORY') {

                /*$data = \App\Models\Distributor\DistributorCategories::with(['pageBuilderContent', 'DistributorIds'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor_category';*/

                return redirect()->route('front.distrbCat', array('lng' => $lng, 'catslug' => $slug));
            }




            /** DISTRIBUTOR **/
            if ($table_type == 'DISTRIBUTOR') {

                /*$data = \App\Models\Distributor\Distributor::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor';*/

                $distrb = \App\Models\Distributor\Distributor::where('id', '=', $table_id)->where('status',1)->first();
                if($slug=='multotec-chile'){
                    return redirect()->route('front.distrbCatChile', [
                                
                                'slug' => $slug
                            ]);
                 }
                if (!empty($distrb)) {
                    if (isset($distrb->distrCategorytOne) && isset($distrb->distrCategorytOne->catInfo)) {
                        $catslug = $distrb->distrCategorytOne->catInfo->slug;
                        return redirect()->route('front.distrb', array('lng' => $lng, 'catslug' => $catslug, 'slug' => $slug));
                    }
                }
            }



            /** DISTRIBUTOR CONTENT **/
            if ($table_type == 'DISTRIBUTOR_CONTENT') {

                /*$data = \App\Models\Distributor\DistributorContents::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor_content';*/

                $disCont = \App\Models\Distributor\DistributorContents::where('id', '=', $table_id)->first();
                if (!empty($disCont)) {
                    if (isset($disCont->distributorInfo) && isset($disCont->distributorInfo->distrCategorytOne) && isset($disCont->distributorInfo->distrCategorytOne->catInfo)) {
                        $disslug = $disCont->distributorInfo->slug;
                        $catslug = $disCont->distributorInfo->distrCategorytOne->catInfo->slug;
                        return redirect()->route('front.distrbCont', array('lng' => $lng, 'catslug' => $catslug, 'disslug' => $disslug, 'slug' => $slug));
                    }
                }
            }




            /** PEOPLES PROFILE CATEGORY **/
            if ($table_type == 'PEOPLE_PROFILE_CATEGORY') {

                $data = \App\Models\PeoplesProfile\PeopleProfileCategories::with(['pageBuilderContent'])
                    ->where('id', '=', $table_id)->where('status',1)->first();
                    if (empty($data)) {
                        abort(404);
                    }
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;

                $view = 'front_end.people_profile.profile_category';
            }




            /** PEOPLES PROFILE **/
            if ($table_type == 'PEOPLE_PROFILE') {

                return redirect()->route('front.profCont', array('lng' => $lng, 'slug' => $slug));
            }
        } else {
            abort(404);
        }

        // dd($view,$DataBag['allData']);
        $DataBag['referral'] = Session::get('referral');

        return view($view, $DataBag);
    }
    /********************************************************************************************************************/






    /**********************************RESOURCE FILES*************************************/
    /************************************************************************************/

    public function allFileCategory($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $fileCategories = \App\Models\Media\FileCategories::where('parent_category_id', '=', '0')
            ->where('status', '=', '1')
            ->where('show_in_gallery', '=', '1')
            ->orderBy('display_order', 'asc')->get();

        $DataBag['fileCategories'] = $fileCategories;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'FILE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.file_categories', $DataBag);
    }


    /** File Download with Category Subcategory with search **/
    public function fileSubcategory(Request $request, $lng, $category_slug, $subcategory_slug = null)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;

        $DataBag['catSlug'] = $category_slug;

        $findCatId = \App\Models\Media\FileCategories::where('slug', '=', trim($category_slug))->first();

        if (!empty($findCatId)) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }

        if ($subcategory_slug != '' && $subcategory_slug != null) {

            $findSubCatId = \App\Models\Media\FileCategories::where('slug', '=', trim($subcategory_slug))->first();
            if (!empty($findSubCatId)) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }
        }

        if ($subcategory_id != 0) {

            $query = DB::table('file_categories_map as fcm')->where('fcm.file_category_id', '=', $category_id)
                ->where('fcm.file_subcategory_id', '=', $subcategory_id)->join('files_master', 'files_master.id', '=', 'fcm.file_id');

            $query = $query->when($request->get('search'), function ($q) use ($request) {

                return $q->where('files_master.name', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.details', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.caption', 'LIKE', '%' . $request->get('search') . '%');
            });

            $downloadBrochures = $query->where('files_master.status', '=', '1')->orderBy('files_master.title', 'asc')
                ->select('files_master.*')->get();

            $page_data = \App\Models\Media\FileCategories::where('id', '=', $subcategory_id)->first();
        } else {

            $query = DB::table('file_categories_map as fcm')->where('fcm.file_category_id', '=', $category_id)
                ->join('files_master', 'files_master.id', '=', 'fcm.file_id');

            $query = $query->when($request->get('search'), function ($q) use ($request) {

                return $q->where('files_master.name', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.details', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('files_master.caption', 'LIKE', '%' . $request->get('search') . '%');
            });

            $downloadBrochures = $query->where('files_master.status', '=', '1')->orderBy('files_master.title', 'asc')
                ->select('files_master.*')->get();

            $page_data = \App\Models\Media\FileCategories::where('id', '=', $category_id)->first();
        }

        $fileSubCategories = \App\Models\Media\FileCategories::where('parent_category_id', '!=', '0')
            ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('name', 'asc')->get();

        $DataBag['fileSubCategories'] = $fileSubCategories;
        $DataBag['downloadBrochures'] = $downloadBrochures;
        $DataBag['page_data'] = $page_data;

        $DataBag['page_metadata'] = $DataBag['page_data'];

        return view('front_end.file_download', $DataBag);
    }

    /*************************************************************************************************************************/
    /*************************************************************************************************************************/



    /*********************** RESOURCE IMAGE ********************************************/
    /**********************************************************************************/
    public function allImgGalCats($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $DataBag['tab_tag'] = 'image_gallery';

        if (isset($_GET['search']) && $_GET['search'] != '') {

            $DataBag['allcats'] = \App\Models\Media\ImageCategories::where('parent_category_id', '=', '0')
                ->where('status', '=', '1')->where('name', 'LIKE', '%' . $_GET['search'] . '%')
                ->where('show_in_gallery', '=', '1')
                ->orderBy('display_order', 'asc')->paginate(12);
        } else {

            $DataBag['allcats'] = \App\Models\Media\ImageCategories::where('parent_category_id', '=', '0')
                ->where('status', '=', '1')
                ->where('show_in_gallery', '=', '1')
                ->orderBy('display_order', 'asc')->paginate(12);
        }

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'IMAGE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.image_video_cats', $DataBag);
    }

    /** Image Display with Category Subcategory with search **/
    public function galSubcategory(Request $request, $lng, $category_slug, $subcategory_slug = null)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;


        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;

        $DataBag['catSlug'] = $category_slug;
        $DataBag['tab_tag'] = 'image_gallery';

        $findCatId = \App\Models\Media\ImageCategories::where('slug', '=', trim($category_slug))->first();

        if (!empty($findCatId)) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }
        if ($subcategory_slug != '' && $subcategory_slug != null) {

            $findSubCatId = \App\Models\Media\ImageCategories::where('slug', '=', trim($subcategory_slug))->first();
            if (!empty($findSubCatId)) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }
        }

        if ($subcategory_id != 0) {

            $query = DB::table('image_category_map as icm')->where('icm.image_category_id', '=', $category_id)
                ->where('icm.image_subcategory_id', '=', $subcategory_id)->join('image', 'image.id', '=', 'icm.image_id')
                ->where('image.status', '=', '1');

            $query = $query->when($request->get('search'), function ($q) use ($request) {

                return $q->where('image.name', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.alt_title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.caption', 'LIKE', '%' . $request->get('search') . '%');
            });

            $viewImages = $query->orderBy('image.name', 'asc')->select('image.*')->paginate(9);

            $page_data = \App\Models\Media\ImageCategories::where('id', '=', $subcategory_id)->first();
        } else {

            $query = DB::table('image_category_map as icm')->where('icm.image_category_id', '=', $category_id)
                ->join('image', 'image.id', '=', 'icm.image_id')->where('image.status', '=', '1');

            $query = $query->when($request->get('search'), function ($q) use ($request) {

                return $q->where('image.name', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.alt_title', 'LIKE', '%' . $request->get('search') . '%')
                    ->orWhere('image.caption', 'LIKE', '%' . $request->get('search') . '%');
            });

            $viewImages = $query->orderBy('image.name', 'asc')->select('image.*')->paginate(9);

            $page_data = \App\Models\Media\ImageCategories::where('id', '=', $category_id)->first();
        }

        $imgSubCategories = \App\Models\Media\ImageCategories::where('parent_category_id', '!=', '0')
            ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('display_order', 'asc')->get();

        $DataBag['imgSubCategories'] = $imgSubCategories;
        $DataBag['viewImages'] = $viewImages;
        $DataBag['page_data'] = $page_data;
        $DataBag['page_metadata'] = $DataBag['page_data'];
        // dd($DataBag);
        return view('front_end.view_image_gallery', $DataBag);
    }

    /*******************************************************************************************************************/
    /*******************************************************************************************************************/



    /*************************** RESOURCE VIDEO ***************************************/
    /*********************************************************************************/
    public function allVidGalCats($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $DataBag['tab_tag'] = 'video_gallery';

        if (isset($_GET['search']) && $_GET['search'] != '') {

            $DataBag['allcats'] = \App\Models\Media\VideoCategories::where('parent_category_id', '=', '0')
                ->where('status', '=', '1')
                ->where('show_in_gallery', '=', '1')
                ->where('name', 'LIKE', '%' . $_GET['search'] . '%')
                ->orderBy('display_order', 'asc')->paginate(12);
        } else {

            $DataBag['allcats'] = \App\Models\Media\VideoCategories::where('parent_category_id', '=', '0')
                ->where('status', '=', '1')
                ->where('show_in_gallery', '=', '1')
                ->orderBy('display_order', 'asc')->paginate(12);
        }

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'VIDEO')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
      
        return view('front_end.image_video_cats', $DataBag);
    }


    public function videoGalSubcategory($lng, $category_slug, $subcategory_slug = null)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;

        $DataBag['catSlug'] = $category_slug;

        $DataBag['tab_tag'] = 'video_gallery';

        $findCatId = \App\Models\Media\VideoCategories::where('slug', '=', trim($category_slug))->first();

        if (!empty($findCatId)) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }

        if ($subcategory_slug != '' && $subcategory_slug != null) {

            $findSubCatId = \App\Models\Media\VideoCategories::where('slug', '=', trim($subcategory_slug))->first();
            if (!empty($findSubCatId)) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name;
                /** Page heading replace when subcat show, display subcat name */
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }
        }

        if ($subcategory_id != 0) {
            $viewVideos = DB::table('video_categories_map as vcm')->where('vcm.video_category_id', '=', $category_id)
                ->where('vcm.video_subcategory_id', '=', $subcategory_id)->join('videos', 'videos.id', '=', 'vcm.video_id')
                ->where('videos.status', '=', '1')->orderBy('videos.created_at', 'desc')->select('videos.*')->paginate(9);

            $page_data = \App\Models\Media\VideoCategories::where('id', '=', $subcategory_id)->first();
        } else {
            $viewVideos = DB::table('video_categories_map as vcm')->where('vcm.video_category_id', '=', $category_id)
                ->join('videos', 'videos.id', '=', 'vcm.video_id')
                ->where('videos.status', '=', '1')->orderBy('videos.created_at', 'desc')->select('videos.*')->paginate(9);

            $page_data = \App\Models\Media\VideoCategories::where('id', '=', $category_id)->first();
        }

        $vidSubCategories = \App\Models\Media\VideoCategories::where('parent_category_id', '!=', '0')
            ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('name', 'asc')->get();

        $DataBag['vidSubCategories'] = $vidSubCategories;
        $DataBag['viewVideos'] = $viewVideos;
        $DataBag['page_data'] = $page_data;

        $DataBag['page_metadata'] = $DataBag['page_data'];

        return view('front_end.view_video_gallery', $DataBag);
    }

    /*********************************************************************************************************************/
    /*********************************************************************************************************************/





    /************************************************TECHNICAL RESOURCE*************************************************/
    public function viewTechnicalResourceList($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\TechResource\TechResource::with(['procatIds', 'FileIds'])
            ->where('status', '=', '1')->orderBy('id', 'desc')->get();

        $DataBag['allData'] = $data;

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds', 'procatIds'])
            ->where('status', '=', '1')->where('tab_section', '=', 'PRODUCT')->orderBy('created_at', 'desc')->paginate(20);

        $DataBag['resFiles'] = $resFiles;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'TECHRES')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.tech_resource.tech_resource_list', $DataBag);
    }

    public function ajxTechnicalResourceList(Request $request, $lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $pcid = trim($request->input('pcid'));
        $seletab = trim($request->input('seletab'));

        $findTR = DB::table('tech_resource_procat_map')->where('product_category_id', '=', $pcid)->get();
        $idsArr = array();

        if (!empty($findTR)) {
            foreach ($findTR as $v) {
                if ($v->tech_resource_id != '0') {
                    array_push($idsArr, $v->tech_resource_id);
                }
            }
        }

        $unqIdsArr = array_unique($idsArr);

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])
         // Ordering directly by publish_date
        ->where('tab_section', '=', $seletab)
        ->whereIn('id', $unqIdsArr)
        ->where('status', '=', '1')
        ->orderByRaw('publish_date DESC')  
        ->paginate(20);
      
        $DataBag['resFiles'] = $resFiles;

        $render = view('front_end.render.tech_resource_files', $DataBag)->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    }

    public function ajxTechnicalResourceTab(Request $request, $lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $seletab = trim($request->input('seletab'));

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])
            ->where('tab_section', '=', $seletab)->where('status', '=', '1')->orderBy('created_at', 'desc')->paginate(20);

        $DataBag['resFiles'] = $resFiles;

        $render = view('front_end.render.tech_resource_files', $DataBag)->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    }

    public function ajxTechnicalResourceSrc(Request $request, $lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $search = trim($request->input('search'));

        $query = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])->where('status', '=', '1');

        $query = $query->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });

        $query = $query->orderBy('display_order', 'asc')->paginate(20);

        $DataBag['resFiles'] = $query;

        $render = view('front_end.render.tech_resource_files', $DataBag)->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    }

    /************************************************END TECHNICAL RESOURCE***************************************/



    public function webinar($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $query = \App\Models\Webinar::where('status', '=', '1')->with('WebinarCategory')->orderBy('webinar_start_date', 'desc');

        //dd($query);

        // $query = Webinar::where('status', '=', '1')->with('WebinarCategory');
        // $viewWebinars = $query->orderBy('webinar_start_date', 'desc')->paginate(9);

        // $DataBag['viewWebinars'] = $viewWebinars;

        // if( isset($_GET['catid']) && $_GET['catid'] != '' ) {
        //     $query = $query->where( function($query) {
        //         $query = $query->whereHas('categoryIds', function ($query) {
        //             $query->where( 'article_category_id', '=', trim($_GET['catid']) );
        //         });
        //     } );
        // }

        // if( isset($_GET['year']) && isset($_GET['month']) ) {
        //     $query = $query->where( function($query) {
        //         $query = $query->whereMonth( 'publish_date', '=', trim($_GET['month']) );
        //         $query = $query->whereYear( 'publish_date', '=', trim($_GET['year']) );
        //     } );
        // }



        if (isset($_GET['start_date']) && $_GET['start_date'] != '' && isset($_GET['end_date']) && $_GET['end_date'] != '') {

            $query = $query->where(function ($query) {

                $start_date = $_GET['start_date'];
                $end_date = $_GET['end_date'];

                $query =  $query->whereBetween('created_at', [$start_date, $end_date]);
            });
        }

        if (isset($_GET['webinar_category']) && $_GET['webinar_category'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('webinar_category', 'like', '%' . $_GET['webinar_category'] . ',%');
            });
        }


        if (isset($_GET['webinar_topic']) && $_GET['webinar_topic'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('webinar_topic', 'like', '%' . $_GET['webinar_topic'] . ',%');
            });
        }

        if (isset($_GET['webinar_industry']) && $_GET['webinar_industry'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('webinar_industry', 'like', '%' . $_GET['webinar_industry'] . ',%');
            });
        }


        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('name', 'LIKE', '%' . trim($_GET['search']) . '%');
            });
        }

        $articlesData = $query->orderBy('id', 'desc')->paginate(12);
        $DataBag['listData'] = $articlesData;


        foreach ($DataBag['listData'] as $key => $val) {

            $cat = explode(',', $val['webinar_category']);

            $allcat = '';

            foreach ($cat as $row) {
                if ($row != '') {
                    $wbcat = \App\Models\WebinarCategory::where('id', '=', $row)->first();
                    $allcat .= $wbcat->name . ',';
                }
            }




            $DataBag['listData'][$key]['webinarcat'] = $allcat;
            //  $DataBag['listData'][$key]['webinarcat']= $allcat;


        }

        $DataBag['page_tag'] = 'Webinar';

        $articlesCats = \App\Models\WebinarCategory::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $DataBag['listTopic'] = \App\Models\WebinarTopic::where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['listIndustry'] = \App\Models\WebinarIndustry::where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['webinarContent'] = \App\Models\WebinarContent::where('id', '=', '1')->first();


        // $yearArr = array();
        // $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        // ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        // ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        // if( !empty($createdAt) ) {
        //     foreach( $createdAt as $v ) {
        //         $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
        //         array_push( $yearArr , $onlyYear );
        //     }
        // }

        // $uniqueYear = array_unique( $yearArr );
        // $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'ARTICLE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.webinar.webinar_list', $DataBag);
    }

    public function eventDetails($id)
    {
        $DataBag = array();
        $data = DB::table('event_management')
            ->join('event_management_type', 'event_management_type.id', 'event_management.event_type_id')
            ->select(
                'event_management.id',
                'event_management.image',
                'event_management.description',
                'event_management.name',
                'event_management.slug',
                'event_management.event_start_date',
                'event_management.event_end_date',
                'event_management.event_location',
                'event_management.event_url',
                'region_id',
                'country_id',
                'event_management_type.name as event_type',

            )
            ->where('slug', '=', $id)->first();
        $DataBag['allData'] = $data;
        return view('front_end.event.event_details', $DataBag);
    }

    public function getCountries()
    {
        $countries = DB::table('country_codes')->select('country_name', 'dialing_code')->orderBy('country_name')->get();

        return response()->json($countries);
    }
    public function webinarContent($lng, $id)
    {
        //echo "id" .$id;
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;


        $data = \App\Models\Webinar::where('slug', '=', $id)->where('status', '=', '1')->first();





        $webinar_has = Session::get('webinar_has_' . $data->id);
        //   dd($webinar_has,$data->id);
        if (isset($webinar_has) && $webinar_has == $data->id) {
        } else {

            $WB = \App\Models\Webinar::find($data->id);

            $hit = $WB->hit;
            $hit = $hit + 1;
            $WB->hit = $hit;

            $WB->save();

            session(['webinar_has_' . $data->id => $data->id]);
        }

        $DataBag['allData'] = $data;
        // Adding 'meta_title' array to $DataBag['allData']
        $DataBag['allData']['meta_title'] = $data->name ?? '';
        $DataBag['page_metadata'] = $DataBag['allData'];
        //  meta_title
        //   dd($DataBag['page_metadata']);

        //$WB->hit++; 

        //$WB->update(); 
        $contactdata = \App\Models\Content\Contents::with(['pageBuilderContent'])
        ->where('id', '=', 77)->where('status', 1)->first();

 


         $DataBag['contactdata'] = $contactdata;
        return view('front_end.webinar.webinar_content', $DataBag);
    }

    public function webinarVideo($lng, $id)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;


        $data = \App\Models\Webinar::where('id', '=', $id)->where('status', '=', '1')->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];


        return view('front_end.webinar.webinar_video', $DataBag);
    }

    /************************************************NEWS AND ARTICLES*************************************************/
    public function newsArticleLists($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $query = \App\Models\Article\Articles::where('status', '=', '1')->where('parent_language_id', '=', '0');

        if (isset($_GET['catid']) && $_GET['catid'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->whereHas('categoryIds', function ($query) {
                    $query->where('article_category_id', '=', trim($_GET['catid']));
                });
            });
        }

        if (isset($_GET['year'])) {
            $query = $query->where(function ($query) {
                // $query = $query->whereMonth('publish_date', '=', trim($_GET['month']));
                $query = $query->whereYear('publish_date', '=', trim($_GET['year']));
            });
        }

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('name', 'LIKE', '%' . trim($_GET['search']) . '%');
            });
        }

        $articlesData = $query->orderBy('publish_date', 'desc')->paginate(20);
        $DataBag['listData'] = $articlesData;

        $DataBag['page_tag'] = 'Articles & News';

        $articlesCats = \App\Models\Article\ArticleCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $yearArr = array();
        $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
            ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
            ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        if (!empty($createdAt)) {
            foreach ($createdAt as $v) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push($yearArr, $onlyYear);
            }
        }

        $uniqueYear = array_unique($yearArr);
        $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'NEWS')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.news_article.news_articles_list', $DataBag);
    }

    public function articleContent($lng, $slug)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;


        $data = \App\Models\Article\Articles::with(['pageBuilderContent'])
            ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->where('status', '=', '1')->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];

        $articlesCats = \App\Models\Article\ArticleCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $yearArr = array();

        $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
            ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
            ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        if (!empty($createdAt)) {
            foreach ($createdAt as $v) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push($yearArr, $onlyYear);
            }
        }
        $uniqueYear = array_unique($yearArr);
        $DataBag['yearList'] = $uniqueYear;

        return view('front_end.news_article.content_page', $DataBag);
    }
    /************************************************END NEWS & ARTICLES*************************************************/


    /************************************************EVENTS*************************************************/
    public function eventLists(Request $request, $lng = null)
    {
        $year = $request->year;
        $continent_id = $request->continent_id;
        $currentDate = now()->format('Y-m-d');
        $DataBag = array();
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $query = DB::table('event_management')
            ->join('event_management_type', 'event_management_type.id', 'event_management.event_type_id')
            ->select('event_management.id', 'event_management.image', 'event_management.name', 'event_management.slug', 'event_management.event_start_date', 'event_management.event_end_date', 'event_management.event_location', 'event_management.event_url', 'event_management_type.name as event_type', 'region_id', 'country_id')->whereNotNull('event_type_id')

            ->where(function ($query) use ($currentDate) {
                $query->where(function ($query) use ($currentDate) {
                    $query->where('event_end_date', '>=', $currentDate)
                        ->orWhere(function ($query) use ($currentDate) {
                            $query->where('event_start_date', '>=', $currentDate)
                                ->whereNull('event_end_date');
                        });
                });
            })
            ->where(function ($query) use ($year) {
                if (!empty($year)) {
                    $query->where('event_management.event_start_date', 'LIKE', '%' . $year . '%')
                        ->orWhere('event_management.event_end_date', 'LIKE', '%' . $year . '%');
                }
            })
            ->where(function ($query) use ($continent_id) {
                if (!empty($continent_id)) {
                    $query->where('region_id', '=', $continent_id);
                }
            })

            ->orderBy('event_management.event_start_date', 'asc')
            ->paginate(12);
            // ->get();

        $queryPast = DB::table('event_management')
            ->join('event_management_type', 'event_management_type.id', 'event_management.event_type_id')
            ->select('event_management.id', 'event_management.image', 'event_management.name', 'event_management.slug', 'event_management.event_start_date', 'event_management.event_end_date', 'event_management.event_location', 'event_management.event_url', 'event_management_type.name as event_type', 'region_id', 'country_id')->whereNotNull('event_type_id')


            ->where(function ($query) use ($currentDate) {
                $query->where(function ($query) use ($currentDate) {
                    $query->where('event_end_date', '<', $currentDate)
                        ->orWhere(function ($query) use ($currentDate) {
                            $query->where('event_start_date', '<', $currentDate)
                                ->whereNull('event_end_date');
                        });
                });
            })
            ->where(function ($query) use ($year) {
                if (!empty($year)) {
                    $query->where('event_management.event_start_date', 'LIKE', '%' . $year . '%')
                        ->orWhere('event_management.event_end_date', 'LIKE', '%' . $year . '%');
                }
            })
           ->where(function ($query) use ($continent_id) {
                if (!empty($continent_id)) {
                    $query->where('region_id', '=', $continent_id);
                }
            })
            ->orderBy('event_management.event_start_date', 'DESC')
                 ->paginate(12);

        $DataBag['eventData'] = $query;
        $DataBag['queryPast'] = $queryPast;
        if (!empty($continent_id)) {
            $DataBag['continent_id'] = $continent_id;
        }
        $DataBag['webinarContent'] = \App\Models\WebinarContent::where('id', '=', '2')->first();

        return view('front_end.event.event_list', $DataBag);
    }

    public function currentEvents(Request $request, $year)
    {

        $DataBag = array();
        $region_id = $request->region_id;

        $query = DB::table('event_management')
            ->join('event_management_type', 'event_management_type.id', '=', 'event_management.event_type_id')
            ->select(
                'event_management.id',
                'event_management.image',
                'event_management.name',
                'event_management.slug',
                'event_management.event_start_date',
                'event_management.event_end_date',
                'event_management.event_location',
                'event_management.event_url',
                'event_management_type.name as event_type',
                'region_id',
                'country_id'
            )
            ->whereNotNull('event_type_id')
            ->where(function ($query) use ($year) {
                $query->where('event_management.event_start_date', 'LIKE', '%' . $year . '%')
                    ->orWhere('event_management.event_end_date', 'LIKE', '%' . $year . '%');
            })
            ->where(function ($query) use ($region_id) {
                if (!empty($region_id)) {
                    $query->where('region_id', '=', $region_id);
                }
            })
            ->orderBy('event_management.event_start_date', 'asc')
            ->paginate(6);

        $DataBag['eventData'] = $query;
        $DataBag['webinarContent'] = \App\Models\WebinarContent::where('id', '=', '2')->first();
        $DataBag['displayYear'] = $year;
        if (empty($region_id)) {
            $DataBag['selectedRegion'] = '';
        } else {
            $DataBag['selectedRegion'] = $region_id;
        }

        //dd($query);
        return view('front_end.event.current_events_list', $DataBag);
    }
    public function eventContent($lng, $slug)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\Events::with(['pageBuilderContent'])
            ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->where('status', '=', '1')->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];

        $eventCats = \App\Models\EventCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $eventCats;

        $yearArr = array();
        $createdAt = \App\Models\Events::where('status', '=', '1')
            ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
            ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();
        if (!empty($createdAt)) {
            foreach ($createdAt as $v) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push($yearArr, $onlyYear);
            }
        }
        $uniqueYear = array_unique($yearArr);
        $DataBag['yearList'] = $uniqueYear;

        return view('front_end.event.content_page', $DataBag);
    }
    /************************************************END EVENTS*************************************************/


    /************************************************PROFILE*************************************************/
    public function profileLists($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\PeoplesProfile\PeopleProfileCategories::with(['orderByDisplay'])->where('language_id', '=', $getlngid)
            ->where('status', '=', '1')->orderBy('display_order', 'asc')->get();

        $DataBag['allData'] = $data;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'PROFILE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.people_profile.profile_list', $DataBag);
    }

    public function profileContent($lng, $slug)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\PeoplesProfile\PeoplesProfile::with(['pageBuilderContent'])
            ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->where('status', '=', '1')->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];

        return view('front_end.people_profile.profile', $DataBag);
    }
    /************************************************END PROFILE*************************************************/


    /************************************************Distributor Section****************************************/
    public function distributorMap($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'DISTRIBUTOR')->first();
        $DataBag['page_metadata'] = $DataBag['extraContent'];

        $DataBag['allContinents'] = \App\Models\Distributor\DistributorCategories::where('status', '=', '1')
            ->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();

        $map = DB::table('distributor_categories_map as dcm')
            ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
            ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
            ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
            ->where('dc.status', '=', '1')->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '');

        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.slug as branch_slug', 'dc.branch_type', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['map_data'] = json_encode($mapData);

        return view('front_end.distributor.distributor_category', $DataBag);
    }

    public function distributorMapFilter($lng, Request $request)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $continent_id = 0;
        $country_id = 0;
        $branch_id = 0;

        $countries = array();
        $branches = array();

        $click_on = trim($request->get('click_on'));

        if ($request->has('continent_id')) {
            $continent_id = trim($request->get('continent_id'));
            $query = \App\Models\Distributor\Distributor::where('status', '=', '1');
            $query = $query->where(function ($query) use ($continent_id) {
                $query = $query->whereHas('distrCategorytIds', function ($query) use ($continent_id) {
                    $query->where('distributor_category_id', '=', $continent_id);
                });
            });
            $countries = $query->orderBy('name', 'asc')->get();
        }
        if ($request->has('country_id')) {
            $country_id = trim($request->get('country_id'));
            $branches = \App\Models\Distributor\DistributorContents::where('status', '=', '1')
                ->where('distributor_id', '=', $country_id)->orderBy('name', 'asc')->get();
        }
        if ($request->has('branch_id')) {
            $branch_id = trim($request->get('branch_id'));
        }

        $map = DB::table('distributor_categories_map as dcm')->where('dcm.distributor_category_id', '=', $continent_id)
            ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
            ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
            ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
            ->where('dc.status', '=', '1')->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '');

        if ($country_id != 0) {
            $map = $map->where('dc.distributor_id', '=', $country_id);
        }

        if ($branch_id != 0) {
            $map = $map->where('dc.id', '=', $branch_id);
        }

        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.branch_type', 'dc.slug as branch_slug', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['countries'] = $countries;
        $DataBag['branches'] = $branches;
        $DataBag['click_on'] = $click_on;
        $DataBag['map_data'] = $mapData;

        return json_encode($DataBag);
    }

    public function distributorCategory($lng, $cat_slug)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $DataBag['allContinents'] = \App\Models\Distributor\DistributorCategories::where('status', '=', '1')
            ->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();

        $DataBag['currContinent'] = \App\Models\Distributor\DistributorCategories::where('slug', '=', $cat_slug)
            ->where('language_id', '=', $getlngid)->where('status', '=', '1')->first();

        $DataBag['page_metadata'] = $DataBag['currContinent'];

        if (!empty($DataBag['currContinent'])) {
            $continent_id = $DataBag['currContinent']->id;
            $query = \App\Models\Distributor\Distributor::where('status', '=', '1');
            $query = $query->where(function ($query) use ($continent_id) {
                $query = $query->whereHas('distrCategorytIds', function ($query) use ($continent_id) {
                    $query->where('distributor_category_id', '=', $continent_id);
                });
            });
            $countries = $query->orderBy('name', 'asc')->get();
            $DataBag['seleCountries'] = $countries;
        }

        $map = DB::table('distributor_categories_map as dcm')
            ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
            ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
            ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
            ->where('dc.status', '=', '1')->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '')
            ->where('dcat.slug', '=', $cat_slug);

        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.slug as branch_slug', 'dc.branch_type', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['map_data'] = json_encode($mapData);

        return view('front_end.distributor.distributor_category', $DataBag);
    }

    public function distributor($lng, $cat_slug, $distbr_slug)
    { 
        // dd($distbr_slug);
        // if ($distbr_slug === 'multotec-chile') {
        //     return redirect()->route('distributorChile', [
        //         'lng' => 'en',
        //         'slug' => $distbr_slug
        //     ]);
        // }
        
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\Distributor\Distributor::with(['pageBuilderContent'])
            ->where('language_id', '=', $getlngid)->where('language_id', '=', $getlngid)->where('slug', '=', $distbr_slug)
            ->where('status', '=', '1')->first();
     
        $DataBag['allData'] = $data;
        $distributor_id =$data->id??'';

        $DataBag['allDisConts'] = DistributorContents::where('status', '!=', '3')->where('distributor_contents.distributor_id',$data->id)->where('parent_language_id', '=', '0')->get();
        
     

        $DataBag['page_metadata'] = $DataBag['allData'];
        $DataBag['country'] = $distbr_slug??'';
        $DataBag['country_alt'] = $cont_slug??'';

        return view('front_end.distributor.distributor', $DataBag);
    }

    public function distributorChile()
    {
        // dd($distbr_slug);
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;
        $lng='EN';
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
      
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\Distributor\Distributor::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('language_id', '=', $getlngid)->where('slug', '=', 'multotec-chile')
        ->where('distributor.status', '=', '1')->first();

        $DataBag['allData'] = $data;
     
        $DataBag['page_metadata'] = $DataBag['allData'];

        return view('front_end.distributor.distributor_content', $DataBag);
    }


    public function distributorContent($lng, $cat_slug, $distbr_slug, $cont_slug)
    {
     
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\Distributor\DistributorContents::with(['pageBuilderContent'])
            ->where('language_id', '=', $getlngid)->where('slug', '=', $cont_slug)->where('status', '=', '1')->first();
      //  dd($data);
        $DataBag['allData'] = $data;

        $DataBag['country'] = $distbr_slug??'';
        $DataBag['country_alt'] = $cont_slug??'';

        $DataBag['page_metadata'] = $DataBag['allData'];

        //  $DataBag['allDisConts'] = DistributorContents::where('status', '!=', '3')->where('distributor_contents.distributor_id',$data->id)->where('parent_language_id', '=', '0')->get();

        //  dd($DataBag['allDisConts'],$data->id);

         return view('front_end.distributor.distributor_content', $DataBag);
    }
    /************************************************END Distributor Section**********************************/

    public function landingPages( $slug)
    {
            if($slug="multotec-br-landing"){
               return redirect()->route('landingPagesBrazil');
            }
        $page = \App\Models\LandingPages::where('slug', '=', $slug)->first();
        if (!empty($page)) {
            $dirName = $page->dir_name;
            $landingPages_folder = public_path('landing_pages/' . $dirName);
            $asset = asset('public/landing_pages/' . $dirName);

            $pageContent = file_get_contents($landingPages_folder . '/index.html');

            preg_match_all('/href=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }

            preg_match_all('/src=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }

            echo $pageContent;
        }
    }

    public function landingPagesBrazil()
    {

        $page = \App\Models\LandingPages::where('slug', '=', 'brazil')->first();
        if (!empty($page)) {
            $dirName = $page->dir_name;
            $landingPages_folder = public_path('landing_pages/' . $dirName);
            $asset = asset('public/landing_pages/' . $dirName);

            $pageContent = file_get_contents($landingPages_folder . '/index.html');

            preg_match_all('/href=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }

            preg_match_all('/src=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }

            echo $pageContent;
        }
    }

    public function landingPageChile(){
        $slug='multotec-chile-landing';
        $page = \App\Models\LandingPages::where('slug', '=', $slug)->first();
        if (!empty($page)) {
            $dirName = $page->dir_name;
            $landingPages_folder = public_path('landing_pages/' . $dirName);
            $asset = asset('public/landing_pages/' . $dirName);
    
            $pageContent = file_get_contents($landingPages_folder . '/index.html');
    
            preg_match_all('/href=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);
    
            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }
    
            preg_match_all('/src=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);
    
            if (!empty($arr)) {
                foreach (array_unique($arr[1]) as $v) {
                    if ((strpos($v, 'http') === false) && (strpos($v, '#') === false)) {
                        $pageContent = str_replace($v, $asset . '/' . $v, $pageContent);
                    }
                }
            }
    
            echo $pageContent;
        }
    }

    /************ GLOBAL SEARCH ******************/

//     public function globalSearch(Request $request, $lng)
//     {
//         $DataBag = array();

//         if (isset($_GET['q'])) {
//             $search_string = trim($request->get('q'));
//         } else {
//             $search_string = '';
//         }
//         $search_string = str_replace('"', '', $search_string);
//         $keywords = explode(' ', $search_string);
//         // dd($search_string, $keywords);
//         $results_per_page = 25;
//         $current_page = ((isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page'] : 1);
//         $offset = (($current_page > 1) ? ($current_page - 1) * $results_per_page : 0);

//         $conditions = [];
//         foreach ($keywords as $word) {

//              $conditions[] = "name LIKE '%$word%'";
//              $conditions[] = "description LIKE '%$word%'";
//              $conditions[] = "slug LIKE '%$word%'";

            
            
//         }
//         $sql_search_string = implode(' OR ', $conditions);
//         $query = "SELECT * FROM 
//             (
//                 (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, 'product' as type FROM products as pro )
//                 UNION
//                 (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status, 'product_cat' as type  FROM product_categories as prc )
//                 UNION
//                 (SELECT con.id, con.name, con.description, con.slug, con.status, 'content' as type FROM contents as con )
//                 UNION
//                 (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, 'industry' as type FROM industries as indus )
//                 UNION
//                 (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status, 'distributor' as type FROM distributor as distrb )
//                 UNION
//                 (SELECT art.id, art.name, art.description, art.slug, art.status, 'article' as type FROM articles as art )
//                 UNION
//                 (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status, 'event' as type  FROM events as evt )
//                 UNION
//                 (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status, 'people' as type FROM peoples_profile as pepro )
//                 UNION
//                 (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, 'distributor_cat' as type  FROM distributor_category as discat )
//                 UNION
//                 (SELECT flwsht.id, flwsht.name, flwsht.description, flwsht.slug, flwsht.status, 'flowsht' as type  FROM flowsheet as flwsht )
//                 UNION
//                 (SELECT flwshtc.id, flwshtc.name, flwshtc.description, flwshtc.slug, flwshtc.status, 'flowsht_cat' as type  FROM flowsheet_category as flwshtc )
//                 UNION
//                 (SELECT wb.id, wb.name, wb.description, wb.slug, wb.status, 'wbnar' as type FROM webinar as wb ) 
//             ) results WHERE status = '1' AND ($sql_search_string) ORDER BY name,'$search_string' DESC";

// $query_page = "SELECT * FROM 
//             (
//                 (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, 'product' as type FROM products as pro )
//                 UNION
//                 (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status, 'product_cat' as type FROM product_categories as prc )
//                 UNION
//                 (SELECT con.id, con.name, con.description, con.slug, con.status, 'content' as type FROM contents as con )
//                 UNION
//                 (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, 'industry' as type FROM industries as indus )
//                 UNION
//                 (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status, 'distributor' as type FROM distributor as distrb )
//                 UNION
//                 (SELECT art.id, art.name, art.description, art.slug, art.status, 'article' as type FROM articles as art )
//                 UNION
//                 (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status, 'event' as type FROM events as evt )
//                 UNION
//                 (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status, 'people' as type FROM peoples_profile as pepro )
//                 UNION
//                 (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, 'distributor_cat' as type FROM distributor_category as discat )
//                 UNION
//                 (SELECT flwsht.id, flwsht.name, flwsht.description, flwsht.slug, flwsht.status, 'flowsht' as type FROM flowsheet as flwsht )
//                 UNION
//                 (SELECT flwshtc.id, flwshtc.name, flwshtc.description, flwshtc.slug, flwshtc.status, 'flowsht_cat' as type FROM flowsheet_category as flwshtc )
//                 UNION
//                 (SELECT wb.id, wb.name, wb.description, wb.slug, wb.status, 'wbnar' as type FROM webinar as wb )
//                 UNION
//                 (SELECT fs.id, fs.name, fs.description, fs.slug, fs.status, 'fsearch' as type FROM files_search as fs)
//             ) results WHERE status = '1' AND ($sql_search_string)  ORDER BY 
//             CASE type
//                 WHEN 'product' THEN 1
//                 WHEN 'product_cat' THEN 2
//                 WHEN 'content' THEN 3
//                 WHEN 'industry' THEN 4
//                 WHEN 'distributor' THEN 5
//                 WHEN 'article' THEN 6
//                 WHEN 'event' THEN 7
//                 WHEN 'people' THEN 8
//                 WHEN 'distributor_cat' THEN 9
//                 WHEN 'flowsht' THEN 10
//                 WHEN 'flowsht_cat' THEN 11
//                 WHEN 'wbnar' THEN 12
//                 WHEN 'fsearch' THEN 13
//             END ASC,
//             name ASC
//         LIMIT $offset, $results_per_page";
      
//         $results = DB::select(DB::raw($query));
//         $results_page = DB::select(DB::raw($query_page));

//         $options['path'] = 'search';
//         $pagination = new Paginator($results, count($results), $results_per_page, $current_page, $options);

//         $DataBag['allData'] = $results_page;
//         //dd($results_page);
//         $DataBag['pagination'] = $pagination;

//         return view('front_end.search', $DataBag);
//     }

// public function globalSearch(Request $request, $lng)
// {
//     $DataBag = [];

//     // Get search query
//     $search_string = trim($request->get('q', ''));
//     $search_string = str_replace('"', '', $search_string);
//     $keywords = explode(' ', $search_string);

//     $results_per_page = 25;
//     $current_page = $request->get('page', 1);
//     $offset = ($current_page > 1) ? ($current_page - 1) * $results_per_page : 0;

//     $conditions = [];
//     foreach ($keywords as $word) {
//         $word = addslashes($word); // Prevent SQL injection
//         $conditions[] = "name = '$word'"; // Exact match first
//         $conditions[] = "description = '$word'";
//         $conditions[] = "slug = '$word'";

//         $conditions[] = "name LIKE '$word%'"; // Prefix match
//         $conditions[] = "description LIKE '$word%'";
//         $conditions[] = "slug LIKE '$word%'";

//         $conditions[] = "name LIKE '%$word%'"; // Substring match
//         $conditions[] = "description LIKE '%$word%'";
//         $conditions[] = "slug LIKE '%$word%'";
//     }

//     $sql_search_string = implode(' OR ', $conditions);

//     // Main search query
//     $query = "SELECT * FROM 
//         (
//             (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, NULL as ytb_full_link ,NULL as video_link ,'product' as type FROM products as pro )
//             UNION
//             (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status,NULL as ytb_full_link ,NULL as video_link , 'product_cat' as type  FROM product_categories as prc )
//             UNION
//             (SELECT con.id, con.name, con.description, con.slug, con.status, NULL as ytb_full_link  ,NULL as video_link ,'content' as type FROM contents as con )
//             UNION
//             (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, NULL as ytb_full_link ,NULL as video_link ,'industry' as type FROM industries as indus )
//             UNION
//             (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status,NULL as ytb_full_link  ,NULL as video_link , 'distributor' as type FROM distributor as distrb )
//             UNION
//             (SELECT art.id, art.name, art.description, art.slug, art.status,NULL as ytb_full_link  ,NULL as video_link , 'article' as type FROM articles as art )
//             UNION
//             (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status,NULL as ytb_full_link  ,NULL as video_link , 'event' as type  FROM events as evt )
//             UNION
//             (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status,NULL as ytb_full_link  ,NULL as video_link , 'people' as type FROM peoples_profile as pepro )
//             UNION
//             (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, NULL as ytb_full_link  ,NULL as video_link ,'distributor_cat' as type  FROM distributor_category as discat )
//             UNION
//             (SELECT flwsht.id, flwsht.name, flwsht.description, flwsht.slug, flwsht.status,NULL as ytb_full_link  ,NULL as video_link , 'flowsht' as type  FROM flowsheet as flwsht )
//             UNION
//             (SELECT flwshtc.id, flwshtc.name, flwshtc.description, flwshtc.slug, flwshtc.status, NULL as ytb_full_link ,NULL as video_link ,'flowsht_cat' as type  FROM flowsheet_category as flwshtc )
//             UNION
//             (SELECT wb.id, wb.name, wb.description, wb.slug, wb.status,NULL as ytb_full_link  ,NULL as video_link , 'wbnar' as type FROM webinar as wb ) 
//             UNION
//             (SELECT vid_link.id, vid_link.name, vid_link.page_content as description, 0 as slug, vid_link.status,vid_link.ytb_full_link  ,vid_link.video_link , 'vid' as type FROM videos as vid_link )
//         ) results 
//         WHERE status = '1' AND ($sql_search_string) 
//         ORDER BY 
//             CASE 
//                 WHEN name = '$search_string' THEN 1  -- Exact match first
//                 WHEN description = '$search_string' THEN 2  
//                 WHEN slug = '$search_string' THEN 3  
//                 WHEN name LIKE '$search_string%' THEN 4  -- Prefix match
//                 WHEN description LIKE '$search_string%' THEN 5  
//                 WHEN slug LIKE '$search_string%' THEN 6  
//                 WHEN name LIKE '%$search_string%' THEN 7  -- Substring match
//                 WHEN description LIKE '%$search_string%' THEN 8  
//                 WHEN slug LIKE '%$search_string%' THEN 9  
//                 ELSE 10
//             END,
//             name ASC
//         LIMIT $offset, $results_per_page";

//     // Execute the query
//     $results = DB::select(DB::raw($query));

//     // Pagination setup
//     $options['path'] = 'search';
//     $pagination = new Paginator($results, count($results), $results_per_page, $current_page, $options);

//     // Prepare data for the view
//     $DataBag['allData'] = $results;
//     $DataBag['pagination'] = $pagination;
//   //  dd($DataBag['allData']);
//     return view('front_end.search', $DataBag);
// }

//modified global search with pagination

public function globalSearch(Request $request, $lng)
  {  
   
      $DataBag = [];
  
      // Get search query
      $search_string = trim($request->get('q', ''));
      $search_string = str_replace('"', '', $search_string);
      $keywords = explode(' ', $search_string);
  
      $results_per_page = 25;
      $current_page = $request->get('page', 1);
      $offset = ($current_page > 1) ? ($current_page - 1) * $results_per_page : 0;
  
      $conditions = [];
      foreach ($keywords as $word) {
          $word = addslashes($word); // Prevent SQL injection
          $conditions[] = "name = '$word'"; // Exact match first
          $conditions[] = "description = '$word'";
          $conditions[] = "slug = '$word'";
  
          $conditions[] = "name LIKE '$word%'"; // Prefix match
          $conditions[] = "description LIKE '$word%'";
          $conditions[] = "slug LIKE '$word%'";
  
          $conditions[] = "name LIKE '%$word%'"; // Substring match
          $conditions[] = "description LIKE '%$word%'";
          $conditions[] = "slug LIKE '%$word%'";
      }
  
      $sql_search_string = implode(' OR ', $conditions);
       $query="SELECT * FROM 
          (
              (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, NULL as ytb_full_link ,NULL as video_link ,'product' as type FROM products as pro )
              UNION
              (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status,NULL as ytb_full_link ,NULL as video_link , 'product_cat' as type  FROM product_categories as prc )
              UNION
              (SELECT con.id, con.name, con.description, con.slug, con.status, NULL as ytb_full_link  ,NULL as video_link ,'content' as type FROM contents as con )
              UNION
              (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, NULL as ytb_full_link ,NULL as video_link ,'industry' as type FROM industries as indus )
              UNION
              (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status,NULL as ytb_full_link  ,NULL as video_link , 'distributor' as type FROM distributor as distrb )
              UNION
              (SELECT art.id, art.name, art.description, art.slug, art.status,NULL as ytb_full_link  ,NULL as video_link , 'article' as type FROM articles as art )
              UNION
              (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status,NULL as ytb_full_link  ,NULL as video_link , 'event' as type  FROM events as evt )
              UNION
              (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status,NULL as ytb_full_link  ,NULL as video_link , 'people' as type FROM peoples_profile as pepro )
              UNION
              (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, NULL as ytb_full_link  ,NULL as video_link ,'distributor_cat' as type  FROM distributor_category as discat )
              UNION
              (SELECT flwsht.id, flwsht.name, flwsht.description, flwsht.slug, flwsht.status,NULL as ytb_full_link  ,NULL as video_link , 'flowsht' as type  FROM flowsheet as flwsht )
              UNION
              (SELECT flwshtc.id, flwshtc.name, flwshtc.description, flwshtc.slug, flwshtc.status, NULL as ytb_full_link ,NULL as video_link ,'flowsht_cat' as type  FROM flowsheet_category as flwshtc )
              UNION
              (SELECT wb.id, wb.name, wb.description, wb.slug, wb.status,NULL as ytb_full_link  ,NULL as video_link , 'wbnar' as type FROM webinar as wb ) 
              UNION
              (SELECT vid_link.id, vid_link.name, vid_link.page_content as description, 0 as slug, vid_link.status,vid_link.ytb_full_link  ,vid_link.video_link , 'vid' as type FROM videos as vid_link )
          ) results 
          WHERE status = '1' AND ($sql_search_string) 
          ORDER BY 
              CASE 
                  WHEN name = '$search_string' THEN 1  -- Exact match first
                  WHEN description = '$search_string' THEN 2  
                  WHEN slug = '$search_string' THEN 3  
                  WHEN name LIKE '$search_string%' THEN 4  -- Prefix match
                  WHEN description LIKE '$search_string%' THEN 5  
                  WHEN slug LIKE '$search_string%' THEN 6  
                  WHEN name LIKE '%$search_string%' THEN 7  -- Substring match
                  WHEN description LIKE '%$search_string%' THEN 8  
                  WHEN slug LIKE '%$search_string%' THEN 9  
                  ELSE 10
              END,
              name ASC";
      // Main search query
      $query_page = "SELECT * FROM 
          (
              (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, NULL as ytb_full_link ,NULL as video_link ,'product' as type FROM products as pro )
              UNION
              (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status,NULL as ytb_full_link ,NULL as video_link , 'product_cat' as type  FROM product_categories as prc )
              UNION
              (SELECT con.id, con.name, con.description, con.slug, con.status, NULL as ytb_full_link  ,NULL as video_link ,'content' as type FROM contents as con )
              UNION
              (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, NULL as ytb_full_link ,NULL as video_link ,'industry' as type FROM industries as indus )
              UNION
              (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status,NULL as ytb_full_link  ,NULL as video_link , 'distributor' as type FROM distributor as distrb )
              UNION
              (SELECT art.id, art.name, art.description, art.slug, art.status,NULL as ytb_full_link  ,NULL as video_link , 'article' as type FROM articles as art )
              UNION
              (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status,NULL as ytb_full_link  ,NULL as video_link , 'event' as type  FROM events as evt )
              UNION
              (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status,NULL as ytb_full_link  ,NULL as video_link , 'people' as type FROM peoples_profile as pepro )
              UNION
              (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, NULL as ytb_full_link  ,NULL as video_link ,'distributor_cat' as type  FROM distributor_category as discat )
              UNION
              (SELECT flwsht.id, flwsht.name, flwsht.description, flwsht.slug, flwsht.status,NULL as ytb_full_link  ,NULL as video_link , 'flowsht' as type  FROM flowsheet as flwsht )
              UNION
              (SELECT flwshtc.id, flwshtc.name, flwshtc.description, flwshtc.slug, flwshtc.status, NULL as ytb_full_link ,NULL as video_link ,'flowsht_cat' as type  FROM flowsheet_category as flwshtc )
              UNION
              (SELECT wb.id, wb.name, wb.description, wb.slug, wb.status,NULL as ytb_full_link  ,NULL as video_link , 'wbnar' as type FROM webinar as wb ) 
              UNION
              (SELECT vid_link.id, vid_link.name, vid_link.page_content as description, 0 as slug, vid_link.status,vid_link.ytb_full_link  ,vid_link.video_link , 'vid' as type FROM videos as vid_link )
          ) results 
          WHERE status = '1' AND ($sql_search_string) 
          ORDER BY 
              CASE 
                  WHEN name = '$search_string' THEN 1  -- Exact match first
                  WHEN description = '$search_string' THEN 2  
                  WHEN slug = '$search_string' THEN 3  
                  WHEN name LIKE '$search_string%' THEN 4  -- Prefix match
                  WHEN description LIKE '$search_string%' THEN 5  
                  WHEN slug LIKE '$search_string%' THEN 6  
                  WHEN name LIKE '%$search_string%' THEN 7  -- Substring match
                  WHEN description LIKE '%$search_string%' THEN 8  
                  WHEN slug LIKE '%$search_string%' THEN 9  
                  ELSE 10
              END,
              name ASC
          LIMIT $offset, $results_per_page";
  
      // Execute the query
      $results = DB::select($query);
  
      // Pagination setup
      $options['path'] = 'search';
      $pagination = new Paginator($results, count($results), $results_per_page, $current_page, $options);
  
      // Prepare data for the view
      $DataBag['allData'] = $results;
      $DataBag['pagination'] = $pagination;




      $results = DB::select($query);
$results_page = DB::select($query_page);

        $options['path'] = 'search';
        $pagination = new Paginator($results, count($results), $results_per_page, $current_page, $options);

        $DataBag['allData'] = $results_page;
        //dd($results_page);
        $DataBag['pagination'] = $pagination;
    //  dd($DataBag['allData']);
      return view('front_end.search', $DataBag);
  }
    public function notFound($lng)
    {

        return view('errors.404');
    }

    public function demoDev(Request $request, $lng = '')
    {
        if (isset($_SERVER['HTTP_REFERER'])) {


            $r = explode('/', $_SERVER['HTTP_REFERER']);


            if (isset($r[2])) {

                $pattern = '/https/i';
                $r[2] = preg_replace($pattern, '', $r[2]);

                $pattern = '/http/i';
                $r[2] = preg_replace($pattern, '', $r[2]);

                $pattern = '/www./i';
                $r[2] = preg_replace($pattern, '', $r[2]);


                $str = $_SERVER['HTTP_REFERER'];
                $pattern = "/[?]/i";
                $flag = preg_match($pattern, $str);


                if ($flag == 1 && $r[2] == 'multotec.com') {


                    Session::put('referral', $_SERVER['HTTP_REFERER']);
                    setcookie('dipa', $_SERVER['HTTP_REFERER'], time() + (86400 * 30), "/");

                    $CmsLinks = new Referral;
                    $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
                    $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
                    $CmsLinks->save();
                } else if ($r[2] != 'multotec.com') {

                    Session::put('referral', $_SERVER['HTTP_REFERER']);
                    setcookie('dipa', $_SERVER['HTTP_REFERER'], time() + (86400 * 30), "/");

                    $CmsLinks = new Referral;
                    $CmsLinks->referral = $_SERVER['HTTP_REFERER'];
                    $CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
                    $CmsLinks->save();
                }
                // Session::save();









            }
        }

        // if(isset($_SERVER['HTTP_REFERER'])  && $_SERVER['HTTP_REFERER']!='https://www.multotec.com/'  && $_SERVER['HTTP_REFERER']!='https://multotec.com/' && $_SERVER['HTTP_REFERER']!='http://multotec.com/' && $_SERVER['HTTP_REFERER']!='http://multotec.com'  && $_SERVER['HTTP_REFERER']!='https://multotec.com' && $_SERVER['HTTP_REFERER']!='http://www.multotec.com/'  && $_SERVER['HTTP_REFERER']!='https://www.multotec.com/en' && $_SERVER['HTTP_REFERER']!='http://www.multotec.com/en'){

        //     $CmsLinks = new Referral;
        // 	$CmsLinks->referral = $_SERVER['HTTP_REFERER'];
        // 	$CmsLinks->ip = $_SERVER['REMOTE_ADDR'];
        // 	$CmsLinks->save();

        // }


        $DataBag = array();

        $currlngcode = $lng;

        if ($lng == '' && $lng == NULL) {
            $lng = 'en';
        }

        $currlngid = 1;

        if ($lng == 'esl') {
            $currlngid = '5';
        } else if ($lng == 'en') {
            $currlngid = '1';
        } else if ($lng == 'por') {
            $currlngid = '6';
        } else if ($lng == 'ca') {
            $currlngid = '7';
        }

        // if(Session::has('current_lng') && $currlngid) {
        //     $currlngid = Session::get('current_lng');
        //     $currlngcode = Session::get('current_lngcode');
        // } 
        // else{
        Session::put('current_lng', $currlngid);
        Session::put('current_lngcode', $currlngcode);
        // }

        if ($lng == '' && $lng == NULL && count($request->segments()) == 0) {

            $forceURL = $request->url() . '/' . $currlngcode;
            return redirect($forceURL);
        }
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['home_banners'] = \App\Models\Banners::with(['BannerImages'])->get();

        $data = \App\Models\HomeContent::where('language_id', '=', $currlngid)->first();
        $news_limit = $data->news_no;


        // ->where('language_id', '=', $getlngid)
        // ->where('language_id', '=', $getlngid)
        // where('language_id', '=', $getlngid)

        $DataBag['mps'] = \App\Models\MineralProcess::where('status', '=', '1')->orderBy('display_order', 'asc')->get();
        $DataBag['minerals'] = \App\Models\Mineral::where('status', '=', '1')->orderBy('display_order', 'asc')->get();
        $DataBag['news'] = \App\Models\Article\Articles::where('status', '=', '1')->where('language_id', '=', $getlngid)
            ->orderBy('publish_date', 'desc')->take($news_limit)->get();
        $DataBag['map'] = \App\Models\HomeMap::first();

        $DataBag['logos'] = DB::table('home_logo')->where('status', '=', '1')->orderBy('display_order', 'asc')->get();

        $DataBag['allData'] = $data;
        $DataBag['page_metadata'] = $DataBag['allData'];


        Session::get('referral');
        return view('front_end.home', $DataBag);
    }


   public function brochures($lng)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;
$query = BrochureMaster::with([
    'brochureDetails.brochureProducts' => function ($q) {
        $q->orderByRaw('product_id IS NULL, product_id ASC');
    }
])
->where('brochure_master.status', 1)
->orderBy('brochure_master.name', 'ASC');
        // $query = DB::table('brochure_master')->where('status', '=', '1')->orderBy('created_at', 'desc');
      
       // $query = BrochureMaster::with('brochureDetails.brochureProducts')->where('status',1)->orderBy('created_at', 'desc');

        //dd($query);

        // $query = Webinar::where('status', '=', '1')->with('WebinarCategory');
        // $viewWebinars = $query->orderBy('webinar_start_date', 'desc')->paginate(9);

        // $DataBag['viewWebinars'] = $viewWebinars;

        // if( isset($_GET['catid']) && $_GET['catid'] != '' ) {
        //     $query = $query->where( function($query) {
        //         $query = $query->whereHas('categoryIds', function ($query) {
        //             $query->where( 'article_category_id', '=', trim($_GET['catid']) );
        //         });
        //     } );
        // }

        // if( isset($_GET['year']) && isset($_GET['month']) ) {
        //     $query = $query->where( function($query) {
        //         $query = $query->whereMonth( 'publish_date', '=', trim($_GET['month']) );
        //         $query = $query->whereYear( 'publish_date', '=', trim($_GET['year']) );
        //     } );
        // }

         //to be changed

        // if (isset($_GET['start_date']) && $_GET['start_date'] != '' && isset($_GET['end_date']) && $_GET['end_date'] != '') {

        //     $query = $query->where(function ($query) {

        //         $start_date = $_GET['start_date'];
        //         $end_date = $_GET['end_date'];

        //         $query =  $query->whereBetween('created_at', [$start_date, $end_date]);
        //     });
        // }

        // if (isset($_GET['webinar_category']) && $_GET['webinar_category'] != '') {
        //     $query = $query->where(function ($query) {
        //         $query = $query->where('webinar_category', 'like', '%' . $_GET['webinar_category'] . ',%');
        //     });
        // }


        // if (isset($_GET['webinar_topic']) && $_GET['webinar_topic'] != '') {
        //     $query = $query->where(function ($query) {
        //         $query = $query->where('webinar_topic', 'like', '%' . $_GET['webinar_topic'] . ',%');
        //     });
        // }

        // if (isset($_GET['webinar_industry']) && $_GET['webinar_industry'] != '') {
        //     $query = $query->where(function ($query) {
        //         $query = $query->where('webinar_industry', 'like', '%' . $_GET['webinar_industry'] . ',%');
        //     });
        // }
        if (isset($_GET['brochure_type']) && $_GET['brochure_type'] != '') {
            $brochureType = trim($_GET['brochure_type']);
        
            $query->whereHas('brochureDetails', function ($q) use ($brochureType) {
                $q->where('type_id', $brochureType);
            });
        }
        
        if (isset($_GET['brochure_brand']) && $_GET['brochure_brand'] != '') {
            $brochureBrand = trim($_GET['brochure_brand']);
        
            $query->whereHas('brochureDetails', function ($q) use ($brochureBrand) {
                $q->where('brand_id', $brochureBrand);
            });
        }


          if (isset($_GET['brochure_language']) && $_GET['brochure_language'] != '') {
            $brochureLanguage = trim($_GET['brochure_language']);
        
            $query->whereHas('brochureDetails', function ($q) use ($brochureLanguage) {
                $q->where('language_id', $brochureLanguage);
            });
        }

        if (isset($_GET['brochure_product']) && $_GET['brochure_product'] != '') {
            $brochureProducts = trim($_GET['brochure_product']);
        
            $query->whereHas('brochureDetails.brochureProducts', function ($q) use ($brochureProducts) {
                $q->where('product_id', $brochureProducts);
            });
        }

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $query = $query->where(function ($query) {
                $query = $query->where('brochure_master.name', 'LIKE', '%' . trim($_GET['search']) . '%');
            });
        }

        $articlesData = $query->paginate(12);
        $DataBag['listData'] = $articlesData;
       

        foreach ($DataBag['listData'] as $key => $val) {

            // $cat = explode(',', $val['webinar_category']);

            // $allcat = '';

            // foreach ($cat as $row) {
            //     if ($row != '') {
            //         $wbcat = \App\Models\WebinarCategory::where('id', '=', $row)->first();
            //         $allcat .= $wbcat->name . ',';
            //     }
            // }




            // $DataBag['listData'][$key]['webinarcat'] = $allcat;
            //  $DataBag['listData'][$key]['webinarcat']= $allcat;


        }
  
        $DataBag['page_tag'] = 'Brochures';

        $articlesCats = \App\Models\WebinarCategory::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $DataBag['brochureType'] = DB::table('brochure_type')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['brochureBrand'] = DB::table('brochure_brand')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['brochureLanguage'] = DB::table('brochure_language')->where('status', '=', '1')->get();
        $DataBag['brochureProduct'] = DB::table('brochure_products')->where('status', '=', '1')->get();

        $DataBag['brochureContent'] = \App\Models\BrochureContent::where('id', '=', '1')->first();
        // $yearArr = array();
        // $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        // ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        // ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        // if( !empty($createdAt) ) {
        //     foreach( $createdAt as $v ) {
        //         $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
        //         array_push( $yearArr , $onlyYear );
        //     }
        // }

        // $uniqueYear = array_unique( $yearArr );
        // $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'Brochures')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        // dd($DataBag['listData'] );
        return view('front_end.brochure.brochure_list', $DataBag);
    }



 public function brochureContent($lng,$id){
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $query = DB::table('brochure_master')
        ->select('brochure_master.name','brochure_master.id as brochure_id','brochure_master.description','brochure_master.thumbnail_image','brochure_master.name')
        ->leftjoin('brochure_type','brochure_type.id','brochure_master.type_id')
        ->leftjoin('brochure_brand','brochure_brand.id','brochure_master.brand_id')
        ->leftjoin('brochure_language','brochure_language.id','brochure_master.language_id')
        ->where('brochure_master.slug',$id)->where('brochure_master.status', '=', '1')->orderBy('brochure_master.created_at', 'desc');


        // $query = DB::table('brochure_master')
        // ->select('brochure_master.name','brochure_master.id as brochure_id','brochure_master.description','brochure_language.name as lang_name','brochure_type.name as brochure_type','brochure_master.thumbnail_image','brochure_details.brochure_pdf as upload_doc','brochure_details.download_name','brochure_brand.name as brand_name')
        // ->leftjoin('brochure_details','brochure_details.brochure_id','brochure_master.id')
        // ->leftjoin('brochure_type','brochure_type.id','brochure_details.type_id')
        // ->leftjoin('brochure_brand','brochure_brand.id','brochure_details.brand_id')
        // ->leftjoin('brochure_language','brochure_language.id','brochure_details.language_id')
        // ->where('brochure_master.slug',$id)->where('brochure_master.status', '=', '1')->orderBy('brochure_master.created_at', 'desc');


       
        $articlesData = $query->first();
        $brochure_id=$articlesData->brochure_id??'';
        $fetchBrochureDetails=DB::table('brochure_details')
        ->select('brochure_details.brochure_id','brochure_details.type_id','brochure_details.brand_id','brochure_details.language_id','brochure_type.name as brochure_type','brochure_brand.name as brochure_brand','brochure_size.name as brochure_size','brochure_language.name as brochure_lang','brochure_details.download_name','brochure_details.brochure_pdf','brochure_details.short_url')
        ->leftjoin('brochure_type','brochure_type.id','brochure_details.type_id')
        ->leftjoin('brochure_brand','brochure_brand.id','brochure_details.brand_id')
        ->leftjoin('brochure_language','brochure_language.id','brochure_details.language_id')
        ->leftjoin('brochure_size','brochure_size.id','brochure_details.size_id')
        ->where('brochure_details.brochure_id',$brochure_id)->get();

        $DataBag['listData'] = $articlesData;
        
        $DataBag['fetchBrochureDetails'] = $fetchBrochureDetails;
      
  
        $DataBag['page_tag'] = 'Brochures';

        $articlesCats = \App\Models\WebinarCategory::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $DataBag['brochureType'] = DB::table('brochure_type')->where('brochure_type.id',$id)->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['brochureBrand'] = DB::table('brochure_brand')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['brochureSize'] = DB::table('brochure_size')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['brochureLanguage'] = DB::table('brochure_language')->where('brochure_language.id',$id)->where('status', '=', '1')->get();


        // $yearArr = array();
        // $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        // ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        // ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        // if( !empty($createdAt) ) {
        //     foreach( $createdAt as $v ) {
        //         $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
        //         array_push( $yearArr , $onlyYear );
        //     }
        // }

        // $uniqueYear = array_unique( $yearArr );
        // $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'ARTICLE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        // dd($DataBag['listData'] );
        return view('front_end.brochure.brochure', $DataBag);
    
    }

    public function openBrochure($id = null)
    {   
        // if (!$id) {
        //     abort(404, 'Invalid brochure link');
        // }

        // 🔹 Find brochure by short_url (shortcode only)
        $brochure = BrochureDetails::where('short_url', 'like', '%' . $id . '%')->first();
     
        // 🔹 Generate full PDF URL
        $pdfUrl = asset('public/' . $brochure->brochure_pdf);
      
        // 🔹 Redirect so PDF opens in browser (new tab if link clicked)
        return redirect()->away($pdfUrl);
    }

 public function sendEmailBrochure(Request $request)
{
    try {

        // Insert enquiry into DB
        DB::table('brochure_enquiry')->insert([
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone ?? null,
            'country'         => $request->country ?? null,
            'city'            => $request->city ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Prepare email body
        $brochureLink = $request->brochure_link;

        $mailBody  = 'Dear ' . e($request->recipient_name) . ',<br><br>';
        $mailBody .= 'Here is a link to the Multotec brochure, which I thought you might find interesting:<br><br>';
        $mailBody .= '<a href="' . $brochureLink . '" target="_blank">Click Here</a><br><br>';
        $mailBody .= 'Thank you,<br>Multotec';

        // Send email using Microsoft Graph
        $graphMail = app(MicrosoftGraphMailService::class);

        $graphMail->sendMail(
            trim($request->recipient_email),
            'Your Requested Brochure',
            html_entity_decode($mailBody, ENT_QUOTES)
        );

        return response()->json([
            'status'  => true,
            'message' => 'Email Sent Successfully!'
        ]);

    } catch (\Exception $e) {

        \Log::error('Brochure Email Error: ' . $e->getMessage());

        return response()->json([
            'status'  => false,
            'message' => 'Failed to send email.',
            'error'   => $e->getMessage()
        ], 500);
    }
}
    
    
   public function brochureAjax(Request $request)
{
    // $query = BrochureMaster::with('brochureDetails.brochureProducts')->where('status',1)->orderBy('created_at', 'desc');
$query = BrochureMaster::with([
    'brochureDetails.brochureProducts' => function ($q) {
        $q->orderByRaw('product_id IS NULL, product_id ASC');
    }
])
->where('brochure_master.status', 1)
->orderBy('brochure_master.name', 'ASC');
    // Filter by brochure details
    if ($request->filled('brochure_type')) {
    
        $query->whereHas('brochureDetails', function ($q) use ($request) {
            $q->where('type_id', $request->input('brochure_type'));
        });
    }
    if ($request->filled('brochure_brand')) {
        $query->whereHas('brochureDetails', function ($q) use ($request) {
            $q->where('brand_id', $request->input('brochure_brand'));
        });
    }
    if ($request->filled('brochure_language')) {
        $query->whereHas('brochureDetails', function ($q) use ($request) {
            $q->where('language_id', $request->input('brochure_language'));
        });
    }

    if ($request->filled('brochure_product')) {
        $query->whereHas('brochureDetails.brochureProducts', function ($q) use ($request) {
            $q->where('product_id', $request->input('brochure_product'));
        });
    }

    // Paginate results (6 per page)
    $listData = $query->paginate(12)->appends($request->except('page'));

    $lng = app()->getLocale();
    // $html = '<div class="row" id="listWebinars">';

    // if ($listData->count() > 0) {
    //     foreach ($listData as $v) {
    //         $imageURL = !empty($v->thumbnail_image)
    //             ? asset('public/' . $v->thumbnail_image)
    //             : asset('public/images/default_multotec.jpg');

    //         $html .= '
    //             <div class="col-sm-4 col-md-4">
    //                 <div class="product-card">
    //                     <a href="' . route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) . '">
    //                         <img src="' . $imageURL . '" alt="' . e($v->name) . '">
    //                     </a>
    //                     <div class="product-info">
    //                         <h5>' . e($v->name) . '</h5>
    //                         <a href="' . route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) . '" class="btn-view">View Content</a>
    //                     </div>
    //                 </div>
    //             </div>
    //         ';
    //     }
    // } else {
    //     $html .= '<h3>No Record Found</h3>';
    // }

    // $html .= '</div>';

    // // Pagination buttons
    // $html .= '<div class="prev_next_btn">';
    // if ($listData->previousPageUrl()) {
    //     $html .= '<a href="' . $listData->previousPageUrl() . '"> &lt; Prev </a>';
    // }
    // if ($listData->nextPageUrl()) {
    //     $html .= '<a href="' . $listData->nextPageUrl() . '"> Next &gt; </a>';
    // }
    // $html .= '</div>';
    $html = '<div class="row" id="listWebinars">';

if ($listData->count() > 0) {
    foreach ($listData as $v) {
        $imageURL = !empty($v->thumbnail_image)
            ? asset('public/' . $v->thumbnail_image)
            : asset('public/images/default_multotec.jpg');

        $html .= '
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="product-card h-100">
                    <a href="' . route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) . '">
                        <img src="' . $imageURL . '" alt="' . e($v->name) . '" class="img-fluid">
                    </a>
                    <div class="product-info text-center">
                        <h5>' . e($v->name) . '</h5>
                        <a href="' . route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) . '" class="btn-view">View Content</a>
                    </div>
                </div>
            </div>
        ';
    }
} else {
    $html .= '<h3>No Record Found</h3>';
}

$html .= '</div>';

// Pagination
$html .= '<div class="prev_next_btn text-center mt-3">';
if ($listData->previousPageUrl()) {
    $html .= '<a style="background-color:#fff;color: #008d5c;" href="' . $listData->previousPageUrl() . '" class="btn-view me-2">&lt; Prev</a>';
}
if ($listData->nextPageUrl()) {
    $html .= '<a style="background-color:#fff;color: #008d5c;" href="' . $listData->nextPageUrl() . '" class="btn-view">Next &gt;</a>';
}
$html .= '</div>';


    return response()->json([
        'success' => true,
        'html' => $html
    ]);
}

  public function knowledgehub($lng,Request $request)
    {

        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;
        $fetchlanguage=DB::table('kh_language')->where('name','English')->first();
        $DataBag['language_id'] = $fetchlanguage->id;

    

        $query= DB::table('knowledge_hub')
             ->select('knowledge_hub.name','knowledge_hub.slug','knowledge_hub.id','knowledge_hub.image','knowledge_hub_details.description','knowledge_hub_details.short_description','knowledge_hub_details.webinar_link','knowledge_hub_details.podcast_link','knowledge_hub_details.brochure_link')
             ->leftJoin('knowledge_hub_details','knowledge_hub_details.kh_id','knowledge_hub.id')
             ->leftJoin('kh_language','kh_language.id','knowledge_hub_details.language_id')
             ->where('knowledge_hub.status',1);
            //  ->where('kh_language.name','English');


              // ✅ Filters

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('knowledge_hub.name', 'like', '%' . $search . '%');
        }

    if ($request->filled('kh_language')) {
        $query->where('knowledge_hub_details.language_id', $request->kh_language);
    }
    else{
        $query->where('kh_language.name','English');
    }

    if ($request->filled('kh_product')) {
        $query->where('knowledge_hub_details.product_id', $request->kh_product);
    }

    if ($request->filled('kh_commodity')) {
        $query->where('knowledge_hub_details.commodity_id', $request->kh_commodity);
    }
    if($request->filled('kh_type')){
        if($request->kh_type == 1){
        $query->whereNotNull('knowledge_hub_details.brochure_link');
        }
        if($request->kh_type == 2){
        $query->whereNotNull('knowledge_hub_details.podcast_link');
        }
        if($request->kh_type == 3){
        $query->whereNotNull('knowledge_hub_details.webinar_link');
        }
    }

    if ($request->filled('kh_location')) {
        $query->where('knowledge_hub_details.location_id', $request->kh_location);
    }

      
        
        // if (isset($_GET['brochure_type']) && $_GET['brochure_type'] != '') {
        //     $brochureType = trim($_GET['brochure_type']);
        
        //     $query->whereHas('brochureDetails', function ($q) use ($brochureType) {
        //         $q->where('type_id', $brochureType);
        //     });
        // }
        
        // if (isset($_GET['brochure_brand']) && $_GET['brochure_brand'] != '') {
        //     $brochureBrand = trim($_GET['brochure_brand']);
        
        //     $query->whereHas('brochureDetails', function ($q) use ($brochureBrand) {
        //         $q->where('brand_id', $brochureBrand);
        //     });
        // }


        //   if (isset($_GET['brochure_language']) && $_GET['brochure_language'] != '') {
        //     $brochureLanguage = trim($_GET['brochure_language']);
        
        //     $query->whereHas('brochureDetails', function ($q) use ($brochureLanguage) {
        //         $q->where('language_id', $brochureLanguage);
        //     });
        // }

        // if (isset($_GET['borchure_products']) && $_GET['borchure_products'] != '') {
        //     $brochureProducts = trim($_GET['borchure_products']);
        
        //     $query->whereHas('brochureDetails.brochureProducts', function ($q) use ($brochureProducts) {
        //         $q->where('product_id', $brochureProducts);
        //     });
        // }

        // if (isset($_GET['search']) && $_GET['search'] != '') {
        //     $query = $query->where(function ($query) {
        //         $query = $query->where('brochure_master.name', 'LIKE', '%' . trim($_GET['search']) . '%');
        //     });
        // }
 
        $articlesData = $query->orderBy('knowledge_hub.created_at','desc')->paginate(6);
      
        $DataBag['listData'] = $articlesData;
       

        foreach ($DataBag['listData'] as $key => $val) {

            // $cat = explode(',', $val['webinar_category']);

            // $allcat = '';

            // foreach ($cat as $row) {
            //     if ($row != '') {
            //         $wbcat = \App\Models\WebinarCategory::where('id', '=', $row)->first();
            //         $allcat .= $wbcat->name . ',';
            //     }
            // }




            // $DataBag['listData'][$key]['webinarcat'] = $allcat;
            //  $DataBag['listData'][$key]['webinarcat']= $allcat;


        }
  
        $DataBag['page_tag'] = 'KnowledgeHub';

        $articlesCats = \App\Models\WebinarCategory::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $DataBag['brochureType'] = DB::table('brochure_type')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['brochureBrand'] = DB::table('brochure_brand')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['brochureLanguage'] = DB::table('brochure_language')->where('status', '=', '1')->get();
        $DataBag['brochureProduct'] = DB::table('brochure_products')->where('status', '=', '1')->get();

        $DataBag['brochureContent'] = \App\Models\KnowledgeContent::where('id', '=', '1')->first();



             $DataBag['knowledgeProduct'] = DB::table('kh_products')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['knowledgeCommodity'] = DB::table('kh_commodities')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['knowledgeLocation'] = DB::table('kh_location')->where('status', '=', '1')->get();
        $DataBag['knowledgeLanguage'] = DB::table('kh_language')->where('status', '=', '1')->get();
        // $yearArr = array();
        // $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        // ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        // ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        // if( !empty($createdAt) ) {
        //     foreach( $createdAt as $v ) {
        //         $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
        //         array_push( $yearArr , $onlyYear );
        //     }
        // }

        // $uniqueYear = array_unique( $yearArr );
        // $DataBag['yearList'] = $uniqueYear;
          
        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'KnowledgeHub')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        // dd($DataBag['listData'] );
        return view('front_end.knowledgehub.knowledge_list', $DataBag);
    }

     public function knowledgehubContent(Request $request,$lng="en",$id='NULL'){
        $DataBag = array();

        $device = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode($lng);
        $DataBag['lng_id'] = $getlngid;

        $query =DB::table('knowledge_hub')
             ->select('knowledge_hub.name','knowledge_hub.slug','knowledge_hub.id','knowledge_hub.image','knowledge_hub_details.description','knowledge_hub_details.short_description','knowledge_hub_details.webinar_link','knowledge_hub_details.podcast_link','knowledge_hub_details.brochure_link')
             ->leftJoin('knowledge_hub_details','knowledge_hub_details.kh_id','knowledge_hub.id')
             ->leftJoin('kh_language','kh_language.id','knowledge_hub_details.language_id')
             ->where('knowledge_hub.slug',$id);

             if(isset($request->language_id)  && !empty($request->language_id)) {
            $query=$query->where('kh_language.id',$request->language_id);
             }
           


        // $query = DB::table('brochure_master')
        // ->select('brochure_master.name','brochure_master.id as brochure_id','brochure_master.description','brochure_language.name as lang_name','brochure_type.name as brochure_type','brochure_master.thumbnail_image','brochure_details.brochure_pdf as upload_doc','brochure_details.download_name','brochure_brand.name as brand_name')
        // ->leftjoin('brochure_details','brochure_details.brochure_id','brochure_master.id')
        // ->leftjoin('brochure_type','brochure_type.id','brochure_details.type_id')
        // ->leftjoin('brochure_brand','brochure_brand.id','brochure_details.brand_id')
        // ->leftjoin('brochure_language','brochure_language.id','brochure_details.language_id')
        // ->where('brochure_master.slug',$id)->where('brochure_master.status', '=', '1')->orderBy('brochure_master.created_at', 'desc');


       
        $articlesData = $query->first();
        $brochure_id=$articlesData->brochure_id??'';
      

        $DataBag['listData'] = $articlesData;
        
        $DataBag['fetchBrochureDetails'] = [];
      
  
        $DataBag['page_tag'] = 'Brochures';

        $articlesCats = \App\Models\WebinarCategory::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $DataBag['brochureType'] = DB::table('brochure_type')->where('brochure_type.id',$id)->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $DataBag['brochureBrand'] = DB::table('brochure_brand')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['brochureSize'] = DB::table('brochure_size')->where('status', '=', '1')->orderBy('created_at', 'desc')->get();


        $DataBag['brochureLanguage'] = DB::table('brochure_language')->where('brochure_language.id',$id)->where('status', '=', '1')->get();


        // $yearArr = array();
        // $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        // ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        // ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        // if( !empty($createdAt) ) {
        //     foreach( $createdAt as $v ) {
        //         $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
        //         array_push( $yearArr , $onlyYear );
        //     }
        // }

        // $uniqueYear = array_unique( $yearArr );
        // $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'ARTICLE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        // dd($DataBag['listData'] );
        return view('front_end.knowledgehub.knowledge', $DataBag);
    
    }

   public function knowledgeAjax(Request $request)
{
    $lng = app()->getLocale();

    // ✅ Build Query
    $query = DB::table('knowledge_hub')
        ->select(
            'knowledge_hub.id',
            'knowledge_hub.name',
            'knowledge_hub.slug',
            'knowledge_hub.image',
            'knowledge_hub_details.description',
            'knowledge_hub_details.short_description',
            'knowledge_hub_details.webinar_link',
            'knowledge_hub_details.podcast_link',
            'knowledge_hub_details.brochure_link',
            'knowledge_hub_details.product_id',
            'knowledge_hub_details.commodity_id',
            'knowledge_hub_details.language_id',
            'knowledge_hub_details.location_id'
        )
        ->leftJoin('knowledge_hub_details', 'knowledge_hub_details.kh_id', '=', 'knowledge_hub.id')
        ->leftJoin('kh_language', 'kh_language.id', '=', 'knowledge_hub_details.language_id')
        ->where('knowledge_hub.status', 1);


          if ($request->filled('search')) {
        $query->where('knowledge_hub.name','like', '%'. $request->search .'%');
       }
    // ✅ Filters
    if ($request->filled('kh_language')) {
        $query->where('knowledge_hub_details.language_id', $request->kh_language);
    }

    if ($request->filled('kh_product')) {
        $query->where('knowledge_hub_details.product_id', $request->kh_product);
    }

    if ($request->filled('kh_commodity')) {
        $query->where('knowledge_hub_details.commodity_id', $request->kh_commodity);
    }
    if($request->filled('kh_type')){
        if($request->kh_type == 1){
        $query->whereNotNull('knowledge_hub_details.brochure_link');
        }
        if($request->kh_type == 2){
        $query->whereNotNull('knowledge_hub_details.podcast_link');
        }
        if($request->kh_type == 3){
        $query->whereNotNull('knowledge_hub_details.webinar_link');
        }
    }

    if ($request->filled('kh_location')) {
        $query->where('knowledge_hub_details.location_id', $request->kh_location);
    }

    // ✅ Pagination
    $listData = $query
    ->orderBy('knowledge_hub.created_at','desc')
    ->paginate(6)->appends($request->except('page'));
        
    // ✅ Start HTML
    $html = '<div class="row" id="listWebinars">';

    if ($listData->count() > 0) {

     foreach ($listData as $v) {

    $imageURL = !empty($v->image)
        ? asset('public/' . $v->image)
        : asset('public/images/default_multotec.jpg');

    $url = route('front.knowledgehubCont', [
        'lng' => $lng,
        'id'  => $v->slug,
        'language_id' => $request->kh_language??'' // new query param
    ]);

   $html .= '
<div class="col-sm-4 col-md-4">
    <div class="product-card" style="border:1px solid #ddd; background:#f5f5f5; padding:10px; text-align:center;">
        
        <div>
            <a href="' . $url . '">
                <img src="' . $imageURL . '" alt="Knowledge Hub" style="width:100%; height:200px;object-fit: cover;">
            </a>
        </div>

        <div style="padding:15px;">
            
            <h5 style="font-weight:600; margin-bottom:10px;font-size: 16px !important;">
                <a href="' . $url . '" style="text-decoration:none; color:#000;font-weight: 500;line-height: 1.4;">
                    ' . e($v->name ?? '') . '
                </a>
            </h5>

            <p style="font-size: 13px;line-height: 1.6;font-weight: 500;color: #646464;overflow: hidden;">
                ' . e($v->short_description ?? '') . '
            </p>';
            

// ✅ Icons section (same as Blade)
if (!empty($v->brochure_link) || !empty($v->webinar_link) || !empty($v->podcast_link)) {

    $html .= '<div style="display:flex; justify-content:center; gap:15px;">';



        $html .= '<a href="' . $url . '" 
                    target="_blank" 
                    title="Read" 
                    class="knowledgeicon"
                    style="">
                    <img src="' . asset('public/icons/book.png') . '">
                    <span>Read</span>
                </a>';

    // if (!empty($v->brochure_link)) {
    //     $html .= '<a href="' . htmlspecialchars($v->brochure_link, ENT_QUOTES) . '" 
    //                 target="_blank" 
    //                 title="Read" 
    //                 class="knowledgeicon"
    //                 style="">
    //                 <img src="' . asset('public/icons/book.png') . '">
    //                 <span>Read</span>
    //             </a>';


               
    // }

    if (!empty($v->webinar_link)) {
        $html .= '<a href="' . htmlspecialchars($v->webinar_link, ENT_QUOTES) . '" 
                    target="_blank" 
                    title="Watch" 
                    class="open-video knowledgeicon"
                    style="">
                    <img src="' . asset('public/icons/play-button.png') . '">
                    <span>Watch</span>
                </a>';
    }

    if (!empty($v->podcast_link)) {
        $html .= '<a href="' . htmlspecialchars($v->podcast_link, ENT_QUOTES) . '" 
                    title="Listen" 
                    class="open-audio knowledgeicon"
                    style="">
                    <img src="' . asset('public/icons/headphones.png') . '">
                    <span>Listen</span>
                </a>';
    }

    $html .= '</div>';
}

$html .= '
        </div>
    </div>
</div>';
}

    } else {
        $html .= '<div class="col-12 text-center"><h4>No Record Found</h4></div>';
    }

    $html .= '</div>'; // ✅ close row

    // ✅ Pagination
    $html .= '<div class="text-center mt-3">';

    if ($listData->previousPageUrl()) {
        $html .= '<a style="background-color:#fff;color: #008d5c;" href="' . $listData->previousPageUrl() . '" class="btn btn-outline-secondary me-2">&lt; Prev</a>';
    }

    if ($listData->nextPageUrl()) {
        $html .= '<a style="background-color:#fff;color: #008d5c;" href="' . $listData->nextPageUrl() . '" class="btn btn-outline-secondary">Next &gt;</a>';
    }

    $html .= '</div>';

    // ✅ Response
    return response()->json([
        'success' => true,
        'html'    => $html
    ]);
}

}
