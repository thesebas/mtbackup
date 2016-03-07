<?php
defined('RUN') or die();

function do_incr_backup($source, $dest){
  $info = get_info($source);

  $line = file_get_contents($source);
  $md5 = md5($line);

  if($md5 != @$info['lastmd5']){
    file_put_contents($dest, $line, FILE_APPEND);
    $newinfo = array('lastmd5'=>$md5, 'date'=>date('c'));
    save_info($source, $newinfo);
    #printf("saved [%s] md5: %s @ %s\n", $source, $newinfo['md5'], $newinfo['date']);
    return true;
  }
  #printf("skipped [%s]\n", $source);
  return false;
}

function incr_to_target($filename){
  $info = pathinfo($filename);
  return $info['dirname'].'/'.$info['filename'].".backup.".$info['extension'];
}

function file_with_date($filename){
  $info = pathinfo($filename);
  return $info['dirname'].'/'.$info['filename'].".".date('Y-m-d').".".$info['extension'];
}

function info_filename($source){
  $info = pathinfo($source);
  return $info['dirname'].'/'.$info['filename'].".info";
}

function save_info($source, $data){
  return file_put_contents(info_filename($source), json_encode($data, JSON_PRETTY_PRINT));
}

function get_info($source){
  return @json_decode(@file_get_contents(info_filename($source)), true);
}

function do_full_backup($source, $dest){
  if(file_exists($source)){
    return copy($source, $dest);
  }

  return false;
}

function load_config(){
  $configFile = __DIR__."/config.ini";
  return parse_ini_file($configFile, true);
}
