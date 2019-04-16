<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/15/19
 * Time: 4:38 PM
 */

namespace App\Http\Controllers;

class APIBaseController extends Controller
{
    /**
     * @param string $message
     * @param array $data
     * @return array
     */
    public function responseOK(string $message, array $data): array
    {
        return [
            'success' => true,
            'count' => count($data),
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     * @return array
     */
    public function responseBad(string $message, array $data = []): array
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $response;
    }
}
