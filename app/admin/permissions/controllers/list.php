<?php

$paginator = new Paginator($connect, 'permissions', 10);
$paginator->setSearchColumns(['permission_name']);
$paginator->setOrder('permission_id', 'ASC');

$permissions = $paginator->getResults();

