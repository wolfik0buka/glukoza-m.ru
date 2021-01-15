<?php namespace App\Services\SeoStandart;

use App\Http\Controllers\Controller;
use App\Services\SeoStandart\SeoModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class Seo extends Controller
{

    public $items = [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'breadcrumbs' => [],
        'h1' => '',
        'text' => '',
        'canonical' => false
    ];

    public $url;
    public $isInBlackList;


    public function __construct()
    {
        $this->url = app()->request->getRequestUri();

        // $this->isInBlackList = str_contains($this->url, [
        //     'wp-login', '/netcat', 'cms', 'css', 'wp-includes', 'wp-admin', '.png', '.well-known', 'apple-app-site-association', 'test'
        // ]);
        $this->isInBlackList = false;
    }


    // GETTERS


    /**
     * @return object
     */
    public function all()
    {
        $this->items['breadcrumbs'] = collect($this->items['breadcrumbs'])
            ->map(function($item) { return (object) $item; });

        if (!$this->isInBlackList) {
            $this->setModel();
        }

        return (object) $this->items;
    }


    // SETTERS


    public function setModel()
    {
        try {
            $clear_url = explode('?_openstat', $this->url)[0];
            $clear_url = substr($clear_url, 0, 250);
            $model = SeoModel::firstOrCreate([
                'url' => str_replace(['http://glukoza-med.local', 'http://glukoza-med.ru', 'https://glukoza-med.local', 'https://glukoza-med.ru'], '', $clear_url),
            ]);
            !is_null($model->title) ? $this->items['title'] = $model->title : null;
            !is_null($model->description) ? $this->items['description'] = $model->description : null;
            !is_null($model->keywords) ? $this->items['keywords'] = $model->keywords : null;
            !is_null($model->h1) ? $this->items['h1'] = $model->h1 : null;

            return $this;
        } catch (ModelNotFoundException $ex) {
            return $this;
        }
    }


    /**
     * @param string|array $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->items['title'] = is_array($title) ? implode($title, '') : $title;
        return $this;
    }


    /**
     * @param string|array $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->items['description'] = is_array($description) ? implode($description, '') : $description;
        return $this;
    }


    /**
     * @param String $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->items['keywords'] = is_array($keywords) ? implode($keywords, '') : $keywords;
        return $this;
    }


    /**
     * @param String $text
     * @return $this
     */
    public function setText($text)
    {
        $this->items['text'] = is_array($text) ? implode($text, '') : $text;
        return $this;
    }


    /**
     * @param string|array $h1
     * @return SeoStandart $this
     */
    public function setH1($h1)
    {
        $this->items['h1'] = is_array($h1) ? implode($h1, '') : $h1;
        return $this;
    }


    /**
     * @param String $name
     * @param String $url
     * @return $this
     */
    public function addBreadcrumb($name, $url)
    {
        $this->items['breadcrumbs'][] = compact('name', 'url');
        return $this;
    }


    /**
     * @param arr $elements
     * @return $this
     */
    public function addBreadcrumbs($elements)
    {
        if(count($elements)) {
            foreach($elements as $element) {
                $this->addBreadcrumb($element->name, $element->url);
            }
        }
        return $this;
    }


    /**
     * Add any custom variable into seo array
     *
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function addAny($key, $value)
    {
        $this->items[$key] = is_array($value) ? implode($value, '') : $value;
        return $this;
    }


    /**
     * @param string $url
     * @return $this
     */
    public function setCanonical($url)
    {
        $this->items['canonical'] = $url;
        return $this;
    }


}
