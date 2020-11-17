<?php namespace App\Http\Controllers\Pub;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{

	public function index($parcel_number = false)
	{

	    if ($parcel_number) {
            $parcel = (new \App\Services\Cdek\CdekTrack)
                ->byNumber((string) $parcel_number);
        } else {
            $parcel = false;
        }


        $seo = $this->getSeo();


        if (strlen($parcel_number) > 1 && !$parcel) {
            return view('public.pageOrderTracking.pageOrderTracking', compact('seo', 'parcel', 'parcel_number'))
                ->with('error', 'По номеру '.$parcel_number.' мы не нашли отправлений');
        }

        return view('public.pageOrderTracking.pageOrderTracking', compact('seo', 'parcel', 'parcel_number'));
	}


    public function getSeo()
    {
        return self::seo()
            ->setTitle('Отслеживание отправлений')
            ->setH1('Отслеживание отправлений')
            ->all();
	}


	public function search(Request $request)
	{
		return redirect('/track-orders/'.$request->get('parcel_number'));
	}


}
