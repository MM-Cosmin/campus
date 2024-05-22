<?php
array(  542 => 
array (
  'name' => 'Registration',
  'route' => 'registration',
  'parent_route' => NULL,
  'type' => 1,
),
543 => 
array (
  'name' => 'Student List',
  'route' => 'parentregistration.student-list',
  'parent_route' => 'registration',
  'type' => 2,
),
544 => 
array (
  'name' => 'View',
  'route' => 'parentregistration/student-view',
  'parent_route' => 'parentregistration.student-list',
  'type' => 3,
),
545 => 
array (
  'name' => 'Approve',
  'route' => 'parentregistration/student-approve',
  'parent_route' => 'parentregistration.student-list',
  'type' => 3,
),
546 => 
array (
  'name' => 'Delete',
  'route' => 'parentregistration/student-delete',
  'parent_route' => 'parentregistration.student-list',
  'type' => 3,
),
547 => 
array (
  'name' => 'Settings',
  'route' => 'parentregistration/settings',
  'parent_route' => 'registration',
  'type' => 2,
),
548 => 
array (
  'name' => 'Update',
  'route' => 'parentregistration/settings-update',
  'parent_route' => 'parentregistration/settings',
  'type' => 3,
),
);