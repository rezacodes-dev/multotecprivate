<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; 
use App\Mail\NotifyMail;
use Exception;

class TestEmail extends Controller
{
    public function  test(){
        $emailData = array();
        $emailData['subject'] = "Test Subject";
        $emailData['body'] = "Test by developer";
        $emailData['to_email'] = "heathl@cubicice.com";//heathl@cubicice.com
        $emailData['from_email'] = "heathl@cubicice.com";
        $emailData['from_name'] = "Multotec";
   try{
        Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

            // $message->from($emailData['from_email'], $emailData['from_name']);

            $message->to($emailData['to_email'])->subject($emailData['subject']);
        });
        if (Mail::failures()) {
            echo "Failed";
    }
    else{
        echo "Success";
    }
    }
    catch(Exception $ex){
        echo $ex->getMessage();
    }


    }
}
