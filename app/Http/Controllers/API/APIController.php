<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;

use App\Models\User;
use Response;

/**
 * Base API Controller.
 */
class APIController extends Controller
{
    /**
     * default status code.
     *
     * @var int
     */
    protected $statusCode = 200;


    /**
     * default language.
     *
     * @var string
     */
    protected $lang = 'en';

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }


    /**
     * set language
     *
     * @return statuscode
     */
    public function setLang($lang)
    {
        $this->lang = !empty($lang) ? $lang : 'ar';
        if ($lang != 'ar' && $lang != 'en') {
            $this->lang = 'ar';
        }
        return \App::setlocale($this->lang);
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond.
     *
     * @param string $status
     * @param string $message
     * @param object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($status, $message, $data)
    {
        $array = [
            'status' => $this->statusCode,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($array);
    }


    public function success($message, $data)
    {
        $array = [
            'status' => 200,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($array);
    }

    public function empty_data($message,$data)
    {
        $array = [
            'status' => 200,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($array);
    }

    public function info($message, $data, $info)
    {
        $array = [
            'status' => 200,
            'message' => $message,
            'data' => $data,
            'info' => $info,
        ];
        return response()->json($array);
    }

    /**
     * Respond.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithMessage($message)
    {
        $array = [
            'status' => 200,
            'message' => $message
        ];
        return response()->json($array);
    }

    /**
     * respond with pagincation.
     *
     * @param Paginator $items
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination($items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $items->total(),
                'total_pages' => ceil($items->total() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit' => $items->perPage(),
            ],
        ]);

        return $this->respond($data);
    }

    /**
     * Respond Created.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($data)
    {
        return $this->setStatusCode(201)->respond([
            'data' => $data,
        ]);
    }

    /**
     * Respond Created with data.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreatedWithData($data)
    {
        return $this->setStatusCode(201)->respond($data);
    }

    /**
     * respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        $this->setStatusCode(401);
        $array = [
            'status' => $this->statusCode,
            'message' => $message
        ];
        return response()->json($array);
    }

    /**
     * responsd not found.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized($message = 'Unauthorized')
    {
        $this->setStatusCode(401);
        return $this->respondWithError($message);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        $this->setStatusCode(403);
        $array = [
            'status' => $this->statusCode,
            'message' => $message
        ];
        return response()->json($array);
    }


    public function respondVerify($message)
    {
        $this->setStatusCode(405);
        $array = [
            'status' => $this->statusCode,
            'message' => $message
        ];
        return response()->json($array);
    }

//    public function respondVerify($message = 'Forbidden')
//    {
//        return $this->setStatusCode(405)->respondWithError($message);
//    }


    /**
     * Respond with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithNoContent()
    {
        return $this->setStatusCode(204)->respond(null);
    }

    /**Note this function is same as the below function but instead of responding with error below function returns error json
     * Throw Validation.
     *
     * @param string $message
     *
     * @return mix
     */
    public function throwValidation($message)
    {
        return $this->setStatusCode(422)
            ->respondWithError($message);
    }

    public function checkJWT($token)
    {

        $check = User::where('jwt_token', $token)->first();
        if ($check) {
            return true;
        } else {
            return $this->respondUnauthorized();
        }
    }

}
