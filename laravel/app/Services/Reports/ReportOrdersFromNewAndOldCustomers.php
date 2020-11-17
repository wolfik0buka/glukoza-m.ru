<?php namespace App\Services\Reports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReportOrdersFromNewAndOldCustomers
{

    protected $orders;
    public $phones;
    public $emails;
    public $report;

    public static function render()
    {
        $report = new ReportOrdersFromNewAndOldCustomers();
        $report->phones = new Collection([]);
        $report->emails = new Collection([]);
        $report->getOrders();
        $report->checkOrders();

        $report->orders = false;
        return $report->html();
    }


    public function html()
    {
        $html = '
        <table class="table">
            <thead><tr>
            <th>Год - Месяц</th>            
            <th>Постоянные клиенты</th>
            <th>Новые клиенты</th>            
            </tr></thead>
            <tbody>';
        foreach ($this->report->reverse() as $month => $metrics) {
            $html .= '<tr>
                    <td>'.$month.'</td>
                    <td>'.$metrics['old'].' '.\Lang::choice('клиент|клиента|клиентов', $metrics['old']).' ('.$metrics['old_percent'].'%)</td>
                    <td>'.$metrics['new'].' '.\Lang::choice('клиент|клиента|клиентов', $metrics['new']).' ('.$metrics['new_percent'].'%)</td>
                </tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }


    public function checkOrders()
    {
        $this->report = $this->orders
            ->groupBy(function($order) {
                return Carbon::parse($order->date_order)->format('Y-m');
            })
            ->map(function($orders, $group_name) {
                $new_customers = 0;
                $old_customers = 0;
                foreach($orders as $order) {
                    $this->isOrderFromOldCustomer($order) ? $old_customers++ : $new_customers++;
                }
                return [
                    'old' => $old_customers,
                    'new' => $new_customers,
                    'old_percent' => (int) round(($old_customers / ($old_customers + $new_customers)) * 100),
                    'new_percent' => (int) round(($new_customers / ($new_customers + $old_customers)) * 100),
                ];
            });
    }


    public function getOrders()
    {
        $this->orders = Order::where('date_order', '>', '2016-01-01 00:00:00')
            ->where('date_order', '<', Carbon::now()->startOfMonth()->toDateTimeString())
            ->where('onmedi_order_id', 0)
            ->orderBy('date_order', 'asc')
            ->get();
    }


    public function isOrderFromOldCustomer($order)
    {
        $phone = preg_replace("/[^0-9]/", "", phone_format($order->phone));
        $email = trim(strtolower($order->email));

        $has_phone = false;
        $has_email = false;

        if (!collect(["7", ""])->contains($phone)) {
            $has_phone = collect($this->phones)->contains($phone);
            if (!$has_phone) {
                $this->phones->push($phone);
            }
        }

        if (mb_strlen($email) > 4) {
            $has_email = collect($this->emails)->contains($email);
            if (!$has_email) {
                $this->emails->push($email);
            }
        }

        return $has_email || $has_phone;
    }

    

}