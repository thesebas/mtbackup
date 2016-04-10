<?php
define('RUN', true);

require_once __DIR__.'/common.php';

$config = load_config();

$info = [
  'date' => date('c'),
  'status' => []
];

define('ROOT_DIR', $config['config']['basedir']);
$status = [];
foreach ($config['realtime'] as $dir => $active) {
  if(!$active) {
    $status[$dir] = 'not active';
    continue;
  };

  $filename = ROOT_DIR.$dir.'/realtime.txt';
  $source = incr_to_target($filename);
  $dest = file_with_date($filename);
  if(do_full_backup($source, $dest)){
    unlink(incr_to_target($filename));
    $status[$dir] = [
      'dest' => $dest,
      'status' => true
    ];
  }else{
    $status[$dir] = [
        'status' => false
    ];
  }
}
$info['status']['realtime'] = $status;

$status = [];

foreach ($config['downld08'] as $dir => $active) {
  if(!$active) {
    $status[$dir] = 'not active';
    continue;
  };

  $filename = ROOT_DIR.$dir.'/downld08.txt';
  $dest = file_with_date($filename);
  $res = do_full_backup($filename, $dest);
  $status[$dir] = [
    'dest' => $dest,
    'status' => true
  ];
}
$info['status']['downld08'] = $status;

save_info(__FILE__, $info);
