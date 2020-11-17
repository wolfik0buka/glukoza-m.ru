<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutesWithIndex extends Controller
{

    public function index(Request $request)
    {
        if ($request->has('page')) {
            switch ($request->page):
                case 'cat':
                    return $request->has('idTov') ? $this->productPage($request) : $this->categoryPage($request);
                    break;
                case 'stat':
                    if ($request->has('alias') && $request->alias === 'service') {
                        return redirect('/', 301);
                    }
                    return $this->staticPage($request);
                    break;
                default:
                    return response()->view('errors.404')->setStatusCode(404);
                    break;
            endswitch;
        } else {
            return $this->mainPage($request);
        }
    }



    /**
     * Only correct main page url (or redirect)
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function mainPage($request)
    {
        if ($request->getMethod() != "GET") {
            return response()->view('errors.404')->setStatusCode(404);
        }

        $is_valid = $_SERVER['REQUEST_URI'] === '/';

        if ($is_valid) {
            return Pub\MainPage::index($request);
        } else {
            return redirect(str_replace('index.php', '', url('/')), 301);
        }
    }


    /**
     * Open category page
     *
     * @param Request $request
     * @return mixed
     */
    protected function categoryPage(Request $request)
    {
        if ($this->isValidCategoryUrl()) {
            return Pub\Cats::single($request);
        } else {
            return redirect()->away('http://'.$request->getHost().link_cat($request->cat), 301);
        }
    }



    /**
     * Open product page
     * @param Request $request
     * @return mixed
     */
    protected function productPage(Request $request)
    {
        if ($this->isValidProductUrl()) {
            return Pub\Products::single($request);
        } else {
            return redirect()->away('http://'.$request->getHost().link_product($request->cat, $request->idTov), 301);
        }
    }



    /**
     * Check product url
     * @return bool
     */
    protected function isValidProductUrl()
    {
        preg_match('/\/index\.php\?page=cat&cat=(?>\d*)&idTov=(?>\d*)$/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE, 0);

        return count($matches) > 0;
    }



    /**
     * Check category url
     * @return bool
     */
    protected function isValidCategoryUrl()
    {
        preg_match('/\/index\.php\?page=cat&cat=(?>\d*)$/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE, 0);

        return count($matches) > 0;
    }



    /**
     * Open static page
     * @param $request
     * @return \Illuminate\View\View|string
     */
    protected function staticPage($request)
    {
        return Pub\StaticPages::index($request);
    }


}
