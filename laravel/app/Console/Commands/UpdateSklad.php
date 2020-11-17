<?php namespace App\Console\Commands;

use App\Models\Tovar1c;
use Illuminate\Console\Command;
use XmlParser;
use Nathanmac\Utilities\Parser\Facades\Parser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateSklad extends Command
{
    protected $name = 'update_sklad';
    protected $description = 'Update prices & amounts from XML';

    public $xml;
    public $xml_sklad;


    public function __construct()
    {
        parent::__construct();
    }


    public function fire()
    {
        $this->xml = $this->getXml()->tovars->tovar;

        $this->resetAllPres();

        $this->prepareXml();

        foreach ($this->xml_sklad as $new) {
            $this->info($new['id']. ' - ' .$new['name']);

            try {
                $product = Tovar1c::where('id', $new['id'])->firstOrFail();

                $product->price = $new['price'];
                $product->name = $new['name'];
                $product->pres = 1;
                $product->save();

            } catch (ModelNotFoundException $ex) {
                Tovar1c::create([
                    'id' => $new['id'],
                    'price' => $new['price'],
                    'name' => $new['name'],
                    'pres' => 1
                ]);
            }
        }

    }


    public function prepareXml()
    {
        $filtered = collect([]);

        foreach($this->xml as $xml) {
            $filtered->push([
                'id' => (string) $xml['id'],
                'name' => (string) $xml->name,
                'price' => (integer) $this->getPrice($xml->prices),
                'amount' => $this->getAmount($xml->amounts)
            ]);
        }

        $this->xml_sklad = $filtered->filter(function($item) {
            return $item['price'] > 0 && $item['amount'] > 0;
        });
    }


    public function getPrice($prices)
    {
        foreach ($prices->price as $price) {
            if ($price['type'] == '000000001') {
                return $price;
            }
        }
    }


    public function getAmount($amounts)
    {
        $amount = 0;
        foreach ($amounts->amount as $amount) {
            if ($amount['sklad'] == '000000001' || $amount['sklad'] == '000000002') {
                $amount += intval($amount);
             }
        }

        return (integer) $amount;
    }


    public function resetAllPres()
    {
        Tovar1c::where('pres', 1)->update([
            'pres' => 0
        ]);
    }


    public function getXml()
    {
        return simplexml_load_file(base_path('../share_1c/sklad.xml'));
    }


}
