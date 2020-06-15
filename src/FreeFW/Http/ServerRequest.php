<?php
namespace FreeFW\Http;

/**
 * ServerRequest
 *
 * @author jeromeklam
 */
class ServerRequest
{

    /**
     * Get client IP
     *
     * @return string
     */
    public static function getClientIp(\Psr\Http\Message\ServerRequestInterface $p_request = null)
    {
        //Just get the headers if we can or else use the SERVER global
        if ($p_request !== null) {
            $headers = $p_request->getHeaders();
        } else {
            if (function_exists('apache_request_headers')) {
                $headers = apache_request_headers();
            } else {
                $headers = $_SERVER;
            }
        }
        //Get the forwarded IP if it exists
        $the_ip = '';
        if (array_key_exists('X-Forwarded-For', $headers)) {
            if (is_array($headers['X-Forwarded-For'])) {
                $the_ip = $headers['X-Forwarded-For'][0];
            } else {
                $the_ip = $headers['X-Forwarded-For'];
            }
        } else {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $headers)) {
                if (is_array($headers['HTTP_X_FORWARDED_FOR'])) {
                    $the_ip = $headers['HTTP_X_FORWARDED_FOR'][0];
                } else {
                    $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
                }
            } else {
                if (array_key_exists('X-ClientSide', $headers)) {
                    if (is_array($headers['X-ClientSide'])) {
                        $parts  = explode(':', $headers['X-ClientSide'][0]);
                        $the_ip = $parts[0];
                    } else {
                        $parts  = explode(':', $headers['X-ClientSide']);
                        $the_ip = $parts[0];
                    }
                } else {
                    $the_ip = $_SERVER['REMOTE_ADDR'];
                }
            }
        }
        $the_ip = filter_var($the_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if (!$the_ip) {
            $the_ip = '127.0.0.1';
        }
        return $the_ip;
    }

    /**
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \FreeFW\Http\Cookies|\FreeFW\Http\Cookie
     */
    public static function getRequestCookies(\Psr\Http\Message\ServerRequestInterface $p_request = null)
    {
        $cookies = new \FreeFW\Http\Cookies();
        if ($p_request !== null) {
            $cookieParams = $p_request->getCookieParams();
        } else {
            $cookieParams = $_COOKIES;
        }
        foreach ($cookieParams as $name => $value) {
            $cookies[] = new \FreeFW\Http\Cookie($name, $value);
        }
        return $cookies;
    }
}
