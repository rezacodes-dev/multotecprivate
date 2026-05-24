<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Services\MicrosoftGraphMailService;
class TestEmail extends Controller
{
    // public function test()
    // {
    //     $emailData = [];
    //     $emailData['subject'] = "Test Subject";
    //     $emailData['body'] = "Test by developer";
    //     $emailData['to_email'] = "syedalireza@karmicksolutions.com";

    //     // MUST MATCH SMTP ACCOUNT
    //     $emailData['from_email'] = "NoReplyLeads@multotec.com";
    //     $emailData['from_name'] = "Multotec";

    //     Config::set('mail', [
    //         'default' => 'smtp',

    //         'mailers' => [
    //             'smtp' => [
    //                 'transport' => 'smtp',
    //                 'host' => 'smtp.office365.com',
    //                 'port' => 587,
    //                 'encryption' => 'tls',
    //                 'username' => 'NoReplyLeads@multotec.com',
    //                 'password' => '5h[7#q~IWj]9',
    //                 'timeout' => null,
    //                 'auth_mode' => null,
    //             ],
    //         ],

    //         'from' => [
    //             'address' => 'NoReplyLeads@multotec.com',
    //             'name' => 'Multotec',
    //         ],
    //     ]);

    //     try {

    //         Mail::mailer('smtp')->send(
    //             'emails.accountVerification',
    //             ['emailData' => $emailData],
    //             function ($message) use ($emailData) {

    //                 $message->from(
    //                     $emailData['from_email'],
    //                     $emailData['from_name']
    //                 );

    //                 $message->to($emailData['to_email'])
    //                         ->subject($emailData['subject']);
    //             }
    //         );

    //         echo "Mail Sent Successfully";

    //     } catch (\Exception $ex) {

    //         echo $ex->getMessage();
    //     }
    // }
      public function test(MicrosoftGraphMailService $mailService)
    {
        try {
            return response()->json($mailService->sendMail(
                'syedalireza@karmicksolutions.com',
                'Test Subject',
                'Test by developer'
            ));
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Mail sending failed',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}