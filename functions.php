<?php

include('admin-ui/admin-ui.php');

include('framework/init.php');

include('app/init.php');

//Ensure that a session exists (just in case)
if (!session_id()) {
	session_start();
}
