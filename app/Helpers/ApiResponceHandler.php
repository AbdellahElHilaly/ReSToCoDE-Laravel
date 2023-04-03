<?php
namespace App\Helpers;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

trait ApiResponceHandler
{
    public function apiResponse($data = null, bool $status = true, string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $date = Carbon::now()->setTimezone('GMT');
        $date = $date->format('D, d M Y H:i:s \G\M\T');
        $responseData = [
            'Header'=>
                    [
                        'status' => $status,
                        'code' => $code,
                        'Date' => $date,
                    ],
            'message' => $message,
            'Body'=> $data,
        ];
        if ($data === null) {
            unset($responseData['Body']);
        }

        return response()->json($responseData);
    }
}
