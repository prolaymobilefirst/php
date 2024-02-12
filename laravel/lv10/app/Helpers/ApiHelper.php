<?php

use GuzzleHttp\Client;

/**
 * Print object in human-readible format
 * 
 * @param   mixed   The variable to dump
 * @param   boolean Return string
 * @return  string
 */
if (!function_exists('print_obj'))
{
    function print_obj($obj, $return = FALSE)
    {
        $str = "<pre>";
        if (is_array($obj))
        {
            // to prevent circular references
            if (is_a(current($obj), 'Data_record'))
            {
                foreach($obj as $key => $val)
                {
                    $str .= '['.$key.']';
                    $str .= $val;
                }
            }
            else
            {
                $str .= print_r($obj, TRUE);
            }
        }
        else
        {
            if (is_a($obj, 'Data_record'))
            {
                $str .= $obj;
            }
            else
            {
                $str .= print_r($obj, TRUE);
            }
        }
        $str .= "</pre>";
        if ($return) return $str;
        echo $str;
    }
}



/**
 * Helper function to perform cURL operations in Laravel using Guzzle.
 *
 * @param string $url     The URL to send the request to.
 * @param string $method  The HTTP method (GET, POST, PUT, DELETE, etc.).
 * @param array  $data    The data to send with the request (if any).
 * @param array  $headers The headers to include in the request (if any).
 *
 * @return mixed The response data or false on failure.
 */
function curlRequest($url, $method = 'GET', $data = [], $headers = [])
{
    // Create a new Guzzle HTTP client
    $client = new Client();

    // Prepare the request options
    $options = [
        'headers' => $headers,
    ];

    // Add data to request if it's a POST, PUT, or PATCH request
    if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
        if (is_array($data)) {
            // If data is an array, use form_params
            $options['form_params'] = $data;
        } elseif (is_string($data)) {
            // If data is a JSON string, use body with 'application/json' content type
            $options['body'] = $data;
        }
    }

    try {
        // Make the cURL request
        $response = $client->request($method, $url, $options);

        // Decode and return the response body
        return json_decode($response->getBody(), true);
    } catch (\Exception $e) {
        // Handle any exceptions here (e.g., log the error)
        return false;
    }
}





if(!function_exists('curl_auth_post')){

    function curl_auth_post($params = array(), $isJsonEncode = true) {
        // Check if parameters are set correctly
        if (!is_array($params) || !isset($params['url']) || empty($params['url'])) {
            return array('status' => 'errors', 'status_code' => 400, 'message' => 'Endpoint URL is not valid', 'data' => null);
        }

        // Initialize cURL session
        $curl = curl_init();

        // Prepare POST data
        $postData = isset($params['data']) ? $params['data'] : null;
        if ($postData === null) {
            curl_close($curl);
            return array('status' => 'errors', 'status_code' => 400, 'message' => 'Endpoint Data not valid', 'data' => null);
        }

        // Encode data as JSON if required
        if ($isJsonEncode) {
            $postData = json_encode($postData);
        }

        // Set cURL options
        $curlOptions = array(
            CURLOPT_URL => $params['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => isset($params['headers']) ? $params['headers'] : array(
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode($params['username'] . ':' . $params['password'])
            ),
        );

        // Apply cURL options
        curl_setopt_array($curl, $curlOptions);

        // Execute cURL session and get response
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close cURL session
        curl_close($curl);

        // Return response or errors with proper status code
        if ($err) {
            return array('status' => 'errors', 'status_code' => $httpCode, 'message' => $err, 'data' => null);
        } else {
            return array('status' => 'success', 'status_code' => $httpCode, 'message' => 'data fetched', 'data' => json_decode($response, true));
        }
    }

}
