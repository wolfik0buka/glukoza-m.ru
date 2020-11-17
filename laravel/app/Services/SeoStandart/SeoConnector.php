<?php namespace App\Services\SeoStandart;

use App\Http\Controllers\Controller;
use App\Services\SeoStandart\SeoModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeoConnector extends Controller
{
    protected $TOKEN = 'T8JIPHuClPTAzPqeM8m89rJX9KXylAA7JLVu';
    protected $pass = false;

    public function __construct($token)
    {
         $this->pass = $token === $this->TOKEN;
    }


    public function set($payload)
    {
        if ($this->pass) {
            if ($payload->header('Goal') == 'set') {
                $model = SeoModel::where('url', $payload->url)->first();

                $payload->title ? $model->title = $payload->title : null;
                $payload->description ? $model->description = $payload->description : null;
                $payload->keywords ? $model->keywords = $payload->keywords : null;
                $payload->h1 ? $model->h1 = $payload->h1 : null;
                $payload->worked ? $model->worked = $payload->worked : null;

                if ($model->save()) {
                    return response()
                        ->json(['error' => false, 'message' => 'Saved successful'], 200);
                } else {
                    return response()
                        ->json(['error' => true, 'message' => 'Error on saving'], 200);
                }
            } else {
                return response()
                    ->json(['error' => true, 'message' => 'Goal not defined'], 200);
            }
        } else {
            return response()
                ->json(['error' => true, 'message' => 'Token not valid'], 200);
        }
    }


    public function all()
    {
        if ($this->pass) {
            $data = SeoModel::get();
        } else {
            $data = ['error' => 'Key not set'];
        }

        return response()
            ->make($data, 200);
    }

}
