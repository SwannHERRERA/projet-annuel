<?php
/**
 * TheTvdbApi
 *
 * A simple library to access The TVDB data.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018, Nihilarr
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package   TheTvdbApi
 * @author    Drew Smith
 * @copyright copyright (c) 2018, Nihilarr (https://www.nihilarr.com)
 * @license      http://opensource.org/licenses/MIT	MIT License
 * @link      https://gitlab.com/nihilarr/the-tvdb-api
 * @version   0.0.1
 */

namespace RestAPI;

use Curl\Curl;

/**
 * TheTvdbApi library
 */
class TheTvdbApi
{

    /**
     * Main cURL handle
     * @var resource
     */
    private $request;

    /**
     * TVDB auth token
     * @var string
     */
    private $token = '';

    /**
     * Token creation timestamp
     * @var int
     */
    private $token_created;

    /**
     * Token expiration timestamp
     * @var int
     */
    private $token_expires;

    /**
     * Authenticated
     * @var bool
     */
    private $authenticated = false;

    /**
     * TVDB API URL
     * @var string
     */
    private $api_url = 'https://api.thetvdb.com';


    /**
     * TVDB apikey
     * @var string
     */
    private $api_key;

    /**
     * TVDB userkey
     * @var string
     */
    private $user_key;

    /**
     * TVDB username
     * @var string
     */
    private $username;

    /**
     * Enable/disable adult results
     * @var bool
     */
    private $include_adult = false;

    /**
     * Language identifier
     * @var string
     */
    private $language = 'en';

    /**
     * Enable or disable paged results
     * @var bool
     */
    private $paged = true;

    /**
     * Enable of disable debugging
     * @var bool
     */
    private $debug = false;

    /**
     * Last error message
     * @var string
     */
    private $error;

    /**
     * Last error code
     * @var int
     */
    private $errno;

    /**
     * Request headers
     * @var array
     */
    private $headers = array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Accept-Language' => 'fr'
    );

    /**
     * Constructor
     * @param string $api_key TVDB API key
     * @param string $user_key TVDB user key
     * @param string $username TVDB username
     */
    public function __construct(string $api_key, string $user_key, string $username)
    {
        if ($this->debug) {
            $this->debug_message('Initialize TheTvdbApi.');
        }

        // Set auth vars
        $this->api_key = $api_key;
        $this->user_key = $user_key;
        $this->username = $username;

        // Initialize new cURL session
        $this->request = new Curl();
        $this->request->setHeaders($this->headers);
        $this->request->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        // Set main request error handler
        $this->request->error(function ($request) {
            $this->errno = $request->errorCode;
            $this->error = "[{$this->errno}] {$request->errorMessage} ({$request->url})";

            if ($this->debug) {
                //$this->debug_message($this->error);
            }

            //trigger_error($this->error, E_USER_ERROR);
        });
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Retrieve auth token from TVDB
     * @return bool True if authenticated, false if not
     */
    public function authenticate()
    {
        if (!$this->authenticated) {
            if ($this->debug) {
                $this->debug_message('Attempting to authenticating.');
            }

            $request_uri = $this->build_request_uri('login');

            // Set auth token on successful authentication
            $this->request->success(function ($request) {
                if (isset($request->response->token)) {
                    $this->token_update($request->response->token);

                    $this->authenticated = true;

                    if ($this->debug) {
                        $this->debug_message("Authentication successful for user '{$this->username}'.");
                    }
                }
            });

            // Do authenticate POST to TVDB
            $this->request->post($request_uri, array(
                'apikey' => $this->api_key,
                'userkey' => $this->user_key,
                'username' => $this->username
            ));
        }

        return !!$this->authenticated;
    }

    /**
     * Refresh TVDB auth token
     * @return bool True on success, false on failure
     */
    public function refresh_token()
    {
        if ($result = $this->request('refresh_token')) {
            if (isset($result->token)) {
                $this->token_update($result->token);
                return true;
            }
        }
        return false;
    }

    /**
     * Get episode by the TVDB episode ID.
     * @param string $id TVDB episode ID
     * @param array $params Optional. Extra parameters
     * @return object|bool Result, false on failure
     */
    public function episode(string $id, array $params = array())
    {
        return $this->request("episode/{$id}", $params);
    }

    /**
     * All available languages if $id is omitted. Single language if $id is
     * supplied.
     * @param string $id ID of the language.
     * @return array Array of language objects.
     */
    public function languages(string $id = null)
    {
        $request_uri = 'languages';

        if (null !== $id) {
            $request_uri .= "/{$id}";
        }

        return $this->request($request_uri);
    }

    /**
     * Get array of series by title
     * @param string $title Title of the series
     * @param array $params Optional. Extra parameters
     * @return array|bool Array of objects result, false on failure
     */
    public function search_series(string $title, array $params = array())
    {
        $params['name'] = $title;
        return $this->request('search/series', $params);
    }

    /**
     * Returns an array of parameters to query by.
     * @return array Array of query keys as strings.
     */
    public function search_series_params()
    {
        return $this->request('search/series/params');
    }

    /**
     * Returns a series records that contains all information known about a
     * particular series ID.
     * @param string $id ID of the series.
     * @return object|bool Series, false on failure.
     */
    public function series(string $id)
    {
        return $this->request("series/{$id}");
    }

    /**
     * Returns actors for the given series ID.
     * @param string $id ID of the series.
     * @return array|bool Array of actor objects for the given series ID.
     */
    public function series_actors(string $id)
    {
        return $this->request("series/{$id}/actors");
    }

    /**
     * All episodes for a given series. The optional $params may be used to
     * query against episodes for the given series.Paginated with 100 results
     * per page.
     * @param string $id ID of the series.
     * @param integer $page Page of results to fetch.
     * @param array $params Optional query parameters.
     * @return array|bool Array of episode objects for the given series ID.
     */
    public function series_episodes(string $id, int $page , array $params = array())
    {
        $request_uri = "series/{$id}/episodes";

        if (sizeof($params) > 0) {
            $request_uri .= "/query";
        }

        $params = array('page' => $page);

        return $this->request($request_uri, $params);
    }

    /**
     * Returns the allowed query keys for series episodes.
     * @param string $id ID of the series.
     * @return array An array of query keys.
     */
    public function series_episodes_params(string $id)
    {
        return $this->request("series/{$id}/episodes/query/params");
    }

    /**
     * Returns a summary of the episodes and seasons available for the series.
     * @param string $id ID of the series.
     * @return array Summary of episodes and seasons for the given series.
     */
    public function series_episodes_summary(string $id)
    {
        return $this->request("series/{$id}/episodes/summary");
    }

    /**
     * Returns a series records, filtered by the $keys parameter.
     * @param string $id ID of the series.
     * @param array $keys Array of series keys.
     * @return object Filtered series record.
     */
    public function series_filter(string $id, array $keys)
    {
        $params = array('keys' => implode(',', $keys));
        return $this->request("series/{$id}/filter", $params);
    }

    /**
     * Returns the list of keys available for series filter.
     * @param string $id ID of the series.
     * @return array Array of keys to filter by.
     */
    public function series_filter_params(string $id)
    {
        return $this->request("series/{$id}/filter/params");
    }

    /**
     * Returns a summary of the images for a particular series. The optional
     * $params may be used to query images for the given series ID.
     * @param string $id ID of the series.
     * @param array $params Optional query parameters.
     * @return array Summary of image types and counts for given series.
     */
    public function series_images(string $id, array $params = array())
    {
        $request_uri = "series/{$id}/images";

        if (sizeof($params) > 0) {
            $request_uri .= "/query";
        }
        return $this->request($request_uri, $params);
    }

    /**
     * Returns the allowed query keys for series images.
     * @param  string $id ID of the series.
     * @return array Array of query keys as strings.
     */
    public function series_images_params(string $id)
    {
        return $this->request("series/{$id}/images/query/params");
    }

    /**
     * Get a single series by title.
     * @param string $title Title of the series
     * @param array $params Optional. Extra parameters
     * @return object|bool Result, false on failure
     */
    public function series_title(string $title, array $params = array())
    {
        if ($series = $this->search_series($title, $params)) {
            return $series[0];
        }
        return false;
    }

    /**
     * Return single episode by series title, season number, and episode number.
     * If $episode is null, $season acts as the absolute episode number.
     * @param  string $title Series title
     * @param  int $season Season number, absolute episode number if $episode null
     * @param  int $episode Episode number
     * @param  array $params Optional. Extra parameters
     * @return object|bool Result, false on failure
     */
    public function episode_by_season_episode(string $title, int $season, int $episode = null, array $params = array())
    {
        if ($series = $this->series_title($title)) {
            if (is_null($episode)) {
                $params['absoluteNumber'] = $season;
            } else {
                $params['airedSeason'] = $season;
                $params['airedEpisode'] = $episode;
            }

            if ($episode_data = $this->request("series/{$series->id}/episodes/query", $params)) {
                return $episode_data[0];
            }
        }
        return false;
    }

    /**
     * Returns an array of series that have changed in a maximum of one week
     * blocks since the provided $from_time. The $to_time can be specified to
     * grab results for less than a week. Any timespan larger than a week will
     * be reduced down to one week automatically.
     * @param int $from_time Epoch time to start date range.
     * @param int $to_time Epoch time to end date range (max 1 week from $from_time).
     * @return array Array of Update objects that match the given timeframe.
     */
    public function updated(int $from_time, int $to_time = null)
    {
        $params = array('fromTime' => $from_time);

        if (null !== $to_time) {
            $params['toTime'] = $to_time;
        }

        return $this->request('updated/query', $params);
    }

    /**
     * Returns an array of valid query keys.
     * @return array Array of update objects that match the given timeframe.
     */
    public function updated_params()
    {
        return $this->request('updated/query/params');
    }

    /**
     * Return timestamp the token was created.
     * @return int Timestamp created.
     */
    public function token_created()
    {
        return $this->token_created;
    }

    /**
     * Return timestamp the token will expire.
     * @return int Timestamp expiring.
     */
    public function token_expires()
    {
        return $this->token_expires;
    }

    /**
     * Set the API URL with $api_url parameter. Returns the current API URL if
     * $api_url is omitted.
     * @param string $api_url TVDB API URL.
     * @return object|string Self or current API URL if $api_url omitted.
     */
    public function api_url(string $api_url = null)
    {
        if (null === $api_url) {
            return $this->api_url;
        }

        $this->api_url = $api_url;
        return $this;
    }

    /**
     * Set the API key with $api_key parameter. Returns the current API key if
     * $api_key is omitted.
     * @param string $api_key TVDB API key.
     * @return object|string Self or current API key if $api_key omitted.
     */
    public function api_key(string $api_key = null)
    {
        if (null === $api_key) {
            return $this->api_key;
        }

        $this->api_key = $api_key;
        return $this;
    }

    /**
     * Set the user key with $user_key parameter. Returns the current user key
     * if $user_key is omitted.
     * @param string $user_key TVDB user key.
     * @return object|string Self or current user key if $user_key omitted.
     */
    public function user_key(string $user_key = null)
    {
        if (null === $user_key) {
            return $this->user_key;
        }

        $this->user_key = $user_key;
        return $this;
    }

    /**
     * Set the username with $username parameter. Returns the current user key
     * if $username is omitted.
     * @param string $username TVDB username.
     * @return object|string Self or current username if $username omitted.
     */
    public function username(string $username = null)
    {
        if (null === $username) {
            return $this->username;
        }

        $this->username = $username;
        return $this;
    }


    /**
     * Set the language with $language parameter. Returns the current language
     * if $language is omitted.
     * @param string $language Language code.
     * @return object|string Self or current language if $language omitted.
     */
    public function language(string $language = null)
    {
        if (null === $language) {
            return $this->language;
        }

        $this->language = $language;
        $this->headers['Accept-Language'] = $language;
        return $this;
    }

    /**
     * Toggle status with $include_adult parameter. Returns the current status
     * if $include_adult is omitted.
     * @param bool $include_adult Include adult status.
     * @return object|bool Self or current status if $include_adult omitted.
     */
    public function include_adult(bool $include_adult = null)
    {
        if (null === $include_adult) {
            return !!$this->include_adult;
        }

        $this->include_adult = $include_adult;
        return $this;
    }

    /**
     * Toggle status with $paged parameter. Returns the current status if $paged
     * is omitted.
     * @param bool $paged Paged status.
     * @return object|bool Self or current status if $paged omitted.
     */
    public function paged(bool $paged = null)
    {
        if (null === $paged) {
            return !!$this->paged;
        }

        $this->paged = $paged;
        return $this;
    }

    /**
     * Toggle status with $debug parameter. Returns the current status if $debug
     * is omitted.
     * @param bool $debug Debug status.
     * @return object|bool Self or current status if $debug omitted.
     */
    public function debug(bool $debug = null)
    {
        if (null === $debug) {
            return !!$this->debug;
        }

        $this->debug = $debug;
        return $this;
    }

    /**
     * Return authenticated status.
     * @return bool True if authenticated, false if not.
     */
    public function authenticated()
    {
        return !!$this->authenticated;
    }

    /**
     * Return the last error message.
     * @return string Error message.
     */
    public function error()
    {
        if (isset($this->request->errorMessage)) {
            return $this->request->errorMessage;
        }
    }

    /**
     * Return the last error number.
     * @return int Error number.
     */
    public function errno()
    {
        if (isset($this->request->errorCode)) {
            return $this->request->errorCode;
        }
    }

    /**
     * Close/destroy main cURL handle.
     * @return void
     */
    public function close()
    {
        if ($this->debug) {
            $this->debug_message('Closing TheTvdbApi connections.');
        }

        $this->request->close();
    }

    /**
     * Update auth token variables.
     * @param string $token Auth token.
     * @return void
     */
    private function token_update(string $token)
    {
        $this->token_created = time();
        $this->token_expires = $this->token_created + 86399;
        $this->token = $token;
        $this->headers['Authorization'] = " Bearer {$token}";

        if ($this->debug) {
            $this->debug_message('Auth token has been updated.');
        }
    }

    /**
     * Send GET request to TVDB.
     * @param string $route Route of the request.
     * @param array $params Optional. Extra parameters.
     * @return mixed Array of or object result, false on failure
     */
    private function request(string $route, array $params = array())
    {
        if (!$this->authenticated) {
            $this->authenticate();
        }

        $request_uri = $this->build_request_uri($route, $params);

        // Reload headers to make sure correct token is included
        $this->request->setHeaders($this->headers);

        if ($this->debug) {
            $this->debug_message("Sending {$route} request to {$request_uri}");
        }

        $this->request->success(function ($request) {
            if ($this->debug) {
                $this->debug_message('Request completed successfully');
            }

            return $request->response;
        });

        // Do GET request to TVDB
        $result = $this->request->get($request_uri);
        if (empty($result->data)) {
            return array();
        }
        return $result->data;
    }

    /**
     * Build TVDB request URI.
     * @param string $route Path of the request.
     * @param array $params Parameters for query string.
     * @return string Request URI.
     */
    private function build_request_uri(string $route, array $params = array())
    {
        $request_uri = "{$this->api_url}/{$route}";

        if (sizeof($params) > 0) {
            $request_uri .= '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }

        return $request_uri;
    }

    /**
     * Output debug message to console.
     * @param string $message Message to output.
     * @return void
     */
    private function debug_message(string $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "[{$timestamp}] > {$message}\n";
    }
}
