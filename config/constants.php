<?php

// for user privilege
if (!defined('FORBIDDEN')) define('FORBIDDEN', 0);
if (!defined('ONLY_SEE')) define('ONLY_SEE', 1);
if (!defined('CAN_CRUD')) define('CAN_CRUD', 2);
if (!defined('ALL_ACCESS')) define('ALL_ACCESS', 3);

// warranty
if (!defined('OUT_WARRANTY')) define('OUT_WARRANTY', 0);
if (!defined('IN_WARRANTY')) define('IN_WARRANTY', 1);

if (!defined('NONE')) define('NONE', 0);
if (!defined('PARTIAL')) define('PARTIAL', 1);
if (!defined('FULL')) define('FULL', 2);