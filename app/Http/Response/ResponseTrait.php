<?php
namespace App\Http\Response;

use App\Http\Response\Formats\FormatFactory;

trait ResponseTrait
{
    public function successResponse($data, $format) {
        $response = FormatFactory::make($format)->response($data);

        return $this->sendResponse($response);
    }

    public function errorsResponse(array $errors) {
        $response = new Response(json_encode([
            'errors' => $errors
        ], JSON_PRETTY_PRINT), 400);

        return $this->sendResponse($response);
    }

    public function contentNotFoundResponse() {
        $response = new Response('', 204);

        return $this->sendResponse($response);
    }

    /**
     * @param Response $response
     * @return $this
     */
    private function sendResponse(Response $response) {
        return $response->send();
    }
}