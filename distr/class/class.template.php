<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('SMARTY')){
  include_once SMARTY_DIR . 'Smarty.class.php';
}

class cp_template extends Smarty {

  function cp_template($template_dir) {
  
    $this->template_dir = $template_dir;
    
    $this->assign('tpl_path', '/templates/' . STDTPL);
    $this->assign('base', BASE_DIR);
    $this->assign('ddendyear', mktime(0,0,0,date("m"),date("d"),date("Y")+20));
    $this->assign('ddstartyear', mktime(0,0,0,date("m"),date("d"),date("Y")-10));
    $this->assign('defland', DEFLAND);
    
    if(defined('SESSION')) $this->assign('sess', SESSION);
    
    $this->register_function('cp_perm', 'cp_perm');
    $this->register_function('homelink', 'homelink');
    $this->register_function('redir', 'redir');
    $this->register_function('numformat', 'numFormat');
    
    $this->compile_dir = BASE_DIR . '/templates_c';
    $this->cache_dir   = BASE_DIR . '/templates_c';
  }
}
?>