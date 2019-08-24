<?php

namespace Meveto\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Meveto\Http\Controllers\APIs\APIBaseController;
use Meveto\Models\SecretMessage;
use Meveto\Models\User;
use Validator;

class ClientSecretAPIController extends APIBaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'message'  => 'required',
        ]);
        
        if ($validator->fails()) {
            return $this->respondBadRequest('Validation failed for parameters please review.', $validator->errors()->toArray());
        }

        $message = SecretMessage::create([
            'username' => $request->username,
            'secret'  => utf8_encode($request->message),
        ]);

        return $this->respondSuccess('Secret message saved successfully.', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = SecretMessage::find($id);
        return $this->respondSuccess('Secret message retrieved successfully.', $message);
    }
}
