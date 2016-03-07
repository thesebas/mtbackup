<?php
define('RUN', true);

require_once __DIR__.'/common.php';

$info = [
  'date' => date('c'),
  'status' => []
];

$config = load_config();

define('ROOT_DIR', $config['config']['basedir']);

foreach ($config['realtime'] as $dir => $active) {
  if(!$active) continue;

  $filename = ROOT_DIR.$dir.'/realtime.txt';
  $res = do_incr_backup($filename, incr_to_target($filename));

  $info['status'][$dir] = [
    'status' => $res
  ];
}

save_info(__FILE__, $info);
