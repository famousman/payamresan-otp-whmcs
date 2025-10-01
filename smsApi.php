<?php
/* ========================================
               _
   _ _ ___ ___ ___ ___  |_|___
  | | | -_|   | . |   |_| |  _|
   \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201905
version 2.0
=========================================*/
class smsApi {

    private $username;
    private $password;
    private $from;
    private $client;

    public function __construct($username, $password, $from) {
        $this->username = $username;
        $this->password = $password;
        $this->from = $from;

        try {
 //           $this->client = new SoapClient("https://www.payam-resan.com/ws/v2/ws.asmx?WSDL");
          //  $this->client = new SoapClient(" https://api.sms-webservice.com/api/V3/SendTokenSingle");
        } catch (Exception $e) {
            die("خطا در اتصال به سرویس پیام‌رسان: " . $e->getMessage());
        }
    }
    function extractNumbers($string) {
        // تبدیل اعداد فارسی به انگلیسی
        $persianNumbers = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $englishNumbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $string = str_replace($persianNumbers, $englishNumbers, $string);

        // استخراج اعداد
        preg_match_all('/\d+/', $string, $matches);
        return $matches[0];
    }

    public function send($to, $sms_content) {
        try {
            $code=$this->extractNumbers($sms_content);
            $parameters = array(
            //    'Username'         => $this->username,
                'ApiKey'         => trim($this->password),
                'TemplateKey'     => $this->from,
                'Destination' => str_ireplace("+98","",$to),
                'P1'     => $code[0],
                'P2'     => $code[0],
                'P3'     => $code[0],
               // 'MessageBodie'     => $sms_content,
               // 'Type'             => 1,       // پیام متنی ساده
             //   'AllowedDelay'     => 0        // ارسال فوری
            );




            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://faragirhost.ir/smstest.php?'.http_build_query($parameters),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS =>'{"ApiKey": "'.$this->password.'","Recipients": [{"Sender": 0,"Text": "test","Destination": 0,"UserTraceId": 0}]}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.sms-webservice.com/api/V3/SendTokenSingle?'.http_build_query($parameters),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS =>'{"ApiKey": "'.$this->password.'","Recipients": [{"Sender": 0,"Text": "test","Destination": 0,"UserTraceId": 0}]}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
          ///  echo $response;





       //     $result = $this->client->SendMessage($parameters)->SendMessageResult;
            $result =$response;

            return $result;
        } catch (Exception $e) {
            return 'خطا در ارسال پیامک: ' . $e->getMessage();
        }
    }
}

?>
