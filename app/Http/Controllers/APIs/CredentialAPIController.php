<?php

namespace Meveto\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Meveto\Http\Controllers\APIs\APIBaseController;
use Validator;
use Storage;

class CredentialAPIController extends APIBaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data = [
			'server_public_key' => Storage::disk('local')->get('keys/test_key.pub'),
		];
		return $this->respondSuccess("Server public_key reteived.", $data);
	}
}
