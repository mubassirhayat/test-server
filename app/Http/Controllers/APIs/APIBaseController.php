<?php

namespace Meveto\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Meveto\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;

class APIBaseController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Get the status code for the API
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code for the API
     *
     * @param mixed $statusCode
     * @return $this
     *
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     *
     * @param string $message
     * @param array $headers
     * @return $mixed
     *
     */
    public function respond($data, $headers=[])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondWithError($message, $errors)
    {
        return $this->respond([
            'success'       => false,
            'response_code' => $this->getStatusCode(),
            'message'       => $message,
            'errors'        => $errors,
        ]);
    }

    /**
     * HTTP_OK = 200
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondSuccess($message, $data = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
            'success'       => true,
            'response_code' => $this->getStatusCode(),
            'message'       => $message,
            'data'          => $data,
        ]);
    }

    /**
     * HTTP_CREATED = 201
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondCreated($message, $data = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)->respond([
            'success'       => true,
            'response_code' => $this->getStatusCode(),
            'message'       => $message,
            'data'          => $data,
        ]);
    }

    /**
     * HTTP_BAD_REQUEST = 400
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondBadRequest($message = 'The server cannot or will not process the request due to an apparent client error.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST)->respondWithError($message, $errors);
    }

    /**
     * HTTP_UNAUTHORIZED = 401
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondUnauthorized($message = 'Authentication is required and has failed or has not yet been provided.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)->respondWithError($message, $errors);
    }

    /**
     * HTTP_FORBIDDEN = 403
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondForbidden($message = 'The request was valid, but the server is refusing action.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)->respondWithError($message, $errors);
    }

    /**
     * HTTP_NOT_FOUND = 404
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondNotFound($message = 'The requested resource could not be found but may be available in the future.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message, $errors);
    }

    /**
     * HTTP_UNPROCESSABLE_ENTITY = 422
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondUnprocessable($message = 'The request was well-formed but was unable to be followed due to semantic errors.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message, $errors);
    }

    /**
     * HTTP_INTERNAL_SERVER_ERROR = 500
     *
     * @param string $message
     * @return $mixed
     *
     */
    public function respondInternalError($message = 'An unexpected condition was encountered, no more specific information is available.', $errors = [])
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message, $errors);
    }
}
