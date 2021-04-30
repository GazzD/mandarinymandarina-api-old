<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use MailchimpMarketing\ApiClient;

class MailchimpController extends Controller
{
    public function subscribeToList($email,$listId)
    {
        $mailchimp = new ApiClient();

        $mailchimp->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY', '622ca9cd6e2ed9527a6a94c42bce2cac-us1'),
            'server' => env('MAILCHIMP_SERVER','us1')
        ]);

        try {
            $response = $mailchimp->lists->addListMember($listId, [
                "email_address" => $email,
                "status" => "subscribed",
                "merge_fields" => [
                    "FNAME" => "NoName",
                    "LNAME" => "NoLastName"]
            ]);
            return 'success';
        } catch (MailchimpMarketing\ApiException $e) {
            return $e->getMessage();
        }

    }

    public function subscribeToNewsletter(Request $request)
    {
        $email = $request->input('email');
        return $this->subscribeToList($email, env('MAILCHIMP_NEWSLETTER_LIST_ID','46a2fb1765'));
    }

    public function sendTransactionalEmailCurl(){
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

    }
}
