<?php namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use App;
use App\Http\Controllers\Controller;

class PdfSaleReceipt extends Controller
{
    public $order;
    public $html;


    public function index($id)
    {
        return (new PdfSaleReceipt)
            ->setOrder($id)
            ->render();
    }


    public function setOrder($order_id)
    {
        $this->order = Order::with('productLinks.product', 'deliveryPickupPoint')
            ->where('id', $order_id)
            ->first();

        return $this;
    }


    public function render()
    {
        $this->html = view('pdf.pdfSaleReceipt', [
            'order' => $this->order
        ])->render();

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML($this->html)
            ->setPaper('a4', 'portrait')
            ->setWarnings(true);

        return $pdf->stream();
    }

}