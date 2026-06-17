<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Services\StudentAttachmentService;
use App\Http\Requests\V1\ReviewStudentRequest;
use Illuminate\Http\Request;

class StudentAttachment extends Controller
{
public function __construct(private StudentAttachmentService $studentAttachmentService) {}

    public function getStudentAttachments($personId)
    {
        try{
            $data = $this->studentAttachmentService->getStudentAttachments(auth()->user(),$personId);
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }
    public function reviewStudent(ReviewStudentRequest $request, int $personId)
    {
        try{
            $data=$this->studentAttachmentService->reviewStudent(auth()->user(),$personId,$request->validated());
            return Response::success($data['data'],$data['message'],$data['code']);
        }catch(\Throwable $th){
            return Response::Error([],$th->getMessage());
        }
    }


}
