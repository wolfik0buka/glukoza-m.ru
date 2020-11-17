<?php namespace App\Services\Cdek;

use GuzzleHttp\Client;
use Carbon\Carbon;

class CdekPrint
{

    protected $trackNumber = false;
    protected $date = false;


    /**
     * @param int $trackNumber
     * @return $this
     */
    public function setTrackNumber($trackNumber)
    {
        $this->trackNumber = $trackNumber;
        return $this;
    }


    /**
     * @param bool/str $date
     * @return $this
     */
    public function setDate($date = false)
    {
        if ($date) {
            $this->date = $date;
        } else {
            $this->date = date('Y-m-d');
        }
        return $this;
    }


    /**
     * @return mixed xml
     */
    public function makeXml()
    {
        $xml = new \SimpleXMLElement('<xml/>');
        $xml->addAttribute('encoding', "UTF-8");

        $orders_print = $xml->addChild('OrdersPrint');
        $orders_print->addAttribute("Date", $this->date);
        $orders_print->addAttribute("Account", config('services.cdek.account'));
        $orders_print->addAttribute("Secure", md5($this->date.'&'.config('services.cdek.secure_password')));
        $orders_print->addAttribute("OrderCount", "1");
        $orders_print->addAttribute("CopyCount", "1");

        $order = $orders_print->addChild("Order");
        $order->addAttribute("DispatchNumber", $this->trackNumber);

        return $xml->asXML();
    }


    public function xmlRequest()
    {
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, "https://integration.cdek.ru/orders_print.php" );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ) );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, 'xml_request=' . $this->makeXml() );
        $data = curl_exec( $curl );
        curl_close( $curl );

        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename='".'cargo.guru_ttn_#'.$this->trackNumber .'.pdf'."'");

        print_r($data.$data);
    }


}