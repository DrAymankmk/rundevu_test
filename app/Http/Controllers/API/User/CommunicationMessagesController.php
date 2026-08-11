<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintsBoxResource;
use App\Http\Resources\UserApp\CommunicationsMessagesResource;
use App\Http\Resources\UserApp\RatingResource;
use App\Models\ComplaintBox;
use App\Models\Rating;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class CommunicationMessagesController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    //  get rating
    function communications(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = ComplaintBox::where('user_id', $user->id)->orderBy('id','desc')->paginate(20);
        $complaints_list = CommunicationsMessagesResource::collection($query)->response()->getData();
        return $this->success('communications messages list', $complaints_list);
    }
}
