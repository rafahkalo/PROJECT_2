<?php
namespace App\Traits;
use Illuminate\Http\Request;
trait NotificationTrait {
   
    public  function  send($device_key,$message,$title)
    {
        return response()->json( $this->sendNotification($device_key, $message,$title));

    }
    public  function sendNotification($device_key, $message,$title)
    {
        $SERVER_API_KEY = 'AAAA8IN-81o:APA91bFIQfsLTA38idvyKmnPizvpBcmOh1NgqQ1a94FnA3FjdeR0S3CTAgzalIPNkLnlNXzxLAUK1EPXEGKyg-CXOw3ekwmHAZva2B1gudW-0UX4kublGJ1ZXldkDYFaUx1ynBqDA04Y';
        $URL = 'https://fcm.googleapis.com/fcm/send';
        $post_data = '{
            "to" : "' . $device_key . '",
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '"
                },
          }';

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: key=' . $SERVER_API_KEY;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $ex=curl_exec($crl);
return $ex;


      }













    
}