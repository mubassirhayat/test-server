<?php

namespace Meveto\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Meveto\Http\Controllers\APIs\APIBaseController;
use Meveto\Models\User;
use Validator;

class RegisterAPIController extends APIBaseController
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'			=> 'required',
			'email'			=> 'bail|required|email|unique:users,email',
			'username'		=> 'bail|required|unique:users,username',
			'public_key'	=> 'bail|required|unique:users,public_key',
			'passphrase'	=> 'confirmed',
		]);
		if ($validator->fails()) {
			return $this->respondBadRequest('Validation failed for parameters please review.', $validator->errors()->toArray());
		}

		$user = User::create([
			'name'			=> $request->name,
			'email'			=> $request->email,
			'username'		=> $request->username,
			'public_key'	=> $request->public_key,
			'passphrase'	=> $request->passphrase,
		]);

		return $this->respondSuccess('Registered successfully.', $user);
	}
}
