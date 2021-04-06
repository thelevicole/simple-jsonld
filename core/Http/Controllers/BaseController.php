<?php

namespace SimpleJsonLd\Http\Controllers;

use SimpleJsonLd\Contracts\Prefixer;
use SimpleJsonLd\Contracts\StaticInitiator;

class BaseController {
	use StaticInitiator;
	use Prefixer;
}
