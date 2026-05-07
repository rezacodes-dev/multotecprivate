<?php

namespace App\Http\Controllers;

use App\Mail\NotifyMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Controller
{
  public function test()
{
    $emailData = [];
    $emailData['subject'] = "Test Subject";
    $emailData['body'] = "Test by developer";
    $emailData['to_email'] = "syedalireza@karmicksolutions.com";
    $emailData['from_email'] = "heathl@cubicice.com";
    $emailData['from_name'] = "Multotec";

    // ✅ FULL dynamic config (no env at all)
    Config::set('mail', [
        'default' => 'smtp',

        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => 'smtp.office365.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'NoReplyLeads@multotec.com',
                'password' => '5h[7#q~IWj]9',
                'timeout' => null,
                'auth_mode' => null,
            ],
        ],

        'from' => [
            'address' => 'heathl@cubicice.com',
            'name' => 'Multotec',
        ],
    ]);

    try {
        Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

            $message->from($emailData['from_email'], $emailData['from_name']);
            $message->to($emailData['to_email'])
                    ->subject($emailData['subject']);
        });

        echo "Mail Sent Successfully";
    } catch (\Exception $ex) {
        echo $ex->getMessage();
    }
}
}
