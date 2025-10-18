<?php

$paginator = new Paginator($connect, 'roles', 10);
$paginator->setSearchColumns(['role_name']);
$paginator->setOrder('role_id', 'ASC');

$roles = $paginator->getResults();

