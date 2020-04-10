<?php

return [
  '^/?$'                                      => 'dashboard/index',
  '^(login|logout)$'                          => 'auth/$1',
  '^dashboard$'                               => 'dashboard/index',
  '^charts$'                                  => 'charts/index',
  '^charts/last-year-income$'                 => 'charts/last-year-income',
  '^(transactions|customers|resellers)$'      => '$1/list',
  '^datatables/(customers|transactions|resellers)'      => 'datatables/$1',
  '^customers/(add|update)$'                  => 'customers/$1',
  '^customers/force-update$'                  => 'customers/force-update',
  '^(customers|resellers)/([0-9]+)$'          => '$1/edit/$2',
  '^customers/delete/([0-9]+)$'               => 'customers/delete/$1',
  '^(customers|resellers)/([0-9]+)/status/(true|false)$'  => '$1/status/$2/$3',
  '^tariffs/get-expire-date$'                 => 'tariffs/get-expire-date',
  '^resellers/check-available-balance$'       => 'resellers/check-available-balance',
  '^customers/(validate-mac|validate-login)$' => 'customers/$1',
  '^profile$'                                 => 'resellers/profile',
];



 ?>
