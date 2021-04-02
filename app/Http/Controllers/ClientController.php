<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Client Base URL.
     *
     * @var string
     */
    private $base_url;

    public function __construct()
    {
        $this->base_url = config('app.spa_url');
    }

    /**
     * Get the query string
     *
     * @param   Request  $request
     *
     * @return  string|string
     */
    private function getQuery(Request $request)
    {
        $queries = $request->all();
        
        if (count($queries) > 0) {
            $bag = [];

            foreach ($queries as $key => $value) {
                $bag[] = "$key=$value";
            }

            return "?" . join('&', $bag);
        }

        return null;
    }
    
    /**
     * Redirect to client app.
     *
     * @param   Request  $request
     * @param   string   $url
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    private function away(Request $request, string $url)
    {
        $query = $this->getQuery($request);

        if (is_null($query)) {
            return redirect()->away($url);
        }

        return redirect()->away($url . $query);
    }

    /**
     * Redirect to client index page.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        return $this->away($request, $this->base_url);
    }

    /**
     * Redirect to client reset password page.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        return $this->away($request, $this->base_url . '/reset-password');
    }
}
