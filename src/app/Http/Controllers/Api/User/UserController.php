<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Services\User\UserService;
use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Support\Arr;
use App\Http\Requests\User\SearchUserRequest;

class UserController extends Controller {

    /**
     * @var UserService $_userService
     */
    private $_userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    /**
     * @SWG\Post(
     *   tags={"User"},
     *   path="/user/change-password",
     *   summary="Change user password",
     *   operationId="usersChangePassword",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="current_password",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="new_password",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="new_password_confirmation",
     *     in="formData",
     *     required=true,
     *     type="string"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     * Change active user password to the new one
     *
     * @param UserChangePasswordRequest $request
     * @return void
     */
    public function changePassword(UserChangePasswordRequest $request)
    {
        $password = $request->get('new_password');

        return $this->_userService->changePassword(
            auth()->user(),
            $password
        );
    }

    /**
     * @SWG\Post(
     *   tags={"User"},
     *   path="/user",
     *   summary="Change user first name, last name and avatar",
     *   operationId="userUpdateProfile",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="avatar",
     *     in="formData",
     *     required=false,
     *     type="file"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * Update user profile information and avatar
     *
     * @param UpdateProfileRequest $request
     * @return void
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->_userService->updateProfile(
            auth()->user(),
            Arr::only($request->validated(), [ 'first_name', 'last_name' ]),
            $request->file('avatar')
        );
    }

    /**
     * @SWG\Get(
     *   tags={"User"},
     *   path="/user/search",
     *   summary="Search users that are registered on application",
     *   operationId="usersSearch",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="term",
     *     in="query",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="size",
     *     in="query",
     *     required=false,
     *     type="integer"
     *   ),
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param SearchUserRequest $request
     */
    public function search(SearchUserRequest $request)
    {
        return $this->_userService->search(
            $request->get('term'),
            $request->get('size', 5)
        );
    }

    /**
     * Send verification email to the user
     *
     * @SWG\Post(
     *   tags={"User"},
     *   path="/user/verify/resend",
     *   summary="Resend user verification email",
     *   operationId="userResendVerificationEmail",
     *   produces={"application/json"},
     *   security={{"authorization_token":{}}},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @return void
     */
    public function sendVerifyEmail()
    {
        return $this->_userService->generateAndSendVerifyEmail(
            auth()->user()
        );
    }
}
