<?php namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use App;
use App\Http\Controllers\Controller;

class PdfInvoice extends Controller
{
    public $order;
    public $html;
    public $pdf;


    public function index($id)
    {
        return (new PdfInvoice)->setOrder($id)->render();
    }


    public function setOrder($order_id)
    {
        $this->order = Order::with('productLinks.product', 'deliveryPickupPoint')
            ->where('id', $order_id)
            ->first();

        return $this;
    }


    public function preparePdf()
    {
        $this->html = view('pdf.pdfInvoice', [
            'order' => $this->order
        ])->render();

        $this->pdf = App::make('dompdf.wrapper');

        $this->pdf
            ->loadHTML($this->html)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

    }


    public function saveAndGetPath()
    {
        $this->preparePdf();
        $path = storage_path().'/pdf/invoice_'.$this->order->id.'.pdf';

        $this->pdf->save($path);

        return $path;
    }


    public function render()
    {
        $this->preparePdf();
        return $this->pdf->stream();
    }

}