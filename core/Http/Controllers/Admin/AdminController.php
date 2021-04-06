<?php

namespace SimpleJsonLd\Http\Controllers\Admin;

use SimpleJsonLd\Http\Controllers\BaseController;
use SimpleJsonLd\Wrappers\Hooks;

class AdminController extends BaseController {

	public $enqueuer;

	public function __construct() {
		new SettingsController;
		new PostTypeController;
	}
}
