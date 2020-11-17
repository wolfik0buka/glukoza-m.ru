<?php namespace App\Services\Cdek;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Nathanmac\Utilities\Parser\Formats\XML;

class CdekTrack
{

    protected $date = false;


    public function setDate($date = false)
    {
        if ($date) {
            $this->date = $date;
        } else {
            $this->date = date('Y-m-d');
        }
        return $this;
    }


    public function byNumber($order_id)
    {
        $parcels = collect(
                $this->historyForMonth()['Order']
            )->filter(
                function ($order) use ($order_id) {
                     return $order['@DispatchNumber'] === (string) $order_id;
            }
        );
        if (count($parcels) < 1) {
            return false;
        }
        $parcel = collect($parcels)->first();
        $parcel['Status']['date'] = Carbon::now()->format('d.m.Y');
        $parcel['Status']['time'] = Carbon::now()->format('H:i');
		//print_r($parcel);

if( is_array(current($parcel['Status']['State']))){
        $parcel['Status']['State'] = collect($parcel['Status']['State'])
            ->map(function ($historyItem) {
                $historyItem['date'] = Carbon::parse($historyItem['@Date'])->toDateString();
                $historyItem['time'] = Carbon::parse($historyItem['@Date'])->format('h:i');
                return $historyItem;
            });
}else{
		$parcel['Status']['State'] = collect([['date' => Carbon::parse($parcel['Status']['State']['@Date'])->toDateString(), 'time' => Carbon::parse($parcel['Status']['State']['@Date'])->format('h:i'), '@CityName' => $parcel['Status']['State']['@CityName'], '@Description' => $parcel['Status']['State']['@Description']]]);
}


        return $parcel;

    }


    protected function parseHistoryDate($date)
    {
    }


    public function historyForMonth()
    {
        return $this
            ->setDate(Carbon::now()->addMonth(-1)->toDateString())
            ->trackChanges();
    }


    public function trackChanges()
    {
        $request = (new Client())
            ->get(
                "https://integration.cdek.ru/status_report_h.php".
                '?account='.config('services.cdek.account').
                '&secure='.(md5($this->date.'&'.config('services.cdek.secure_password'))).
                '&datefirst='.$this->date.
                '&showhistory=1',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => '*/*',
                    ],
                ]
            );

        return (new XML)->parse($request->getBody());

    }
}