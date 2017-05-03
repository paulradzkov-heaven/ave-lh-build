<?php
/*::::::::::::::::::::::::::::::::::::::::
 Module name: SysBlock
 Short Desc: System block in any place
 Version: 0.4 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: august 31, 2008
::::::::::::::::::::::::::::::::::::::::*/

class sysblock {

  // Вывод списка текстовых блоков
  function ListBlock($tpl_dir) {
    $SysBlock = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_sysblock");
    while ($result = $sql->fetchrow()) {
      array_push($SysBlock, $result);
    }
    $GLOBALS['tmpl']->assign("SysBlock", $SysBlock);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_list.tpl"));
  }

  // Сохранение текстового блока
  function SaveBlock() {
    $id = (int)$_REQUEST['id'];
    $sysblock_name = addslashes($_POST['sysblock_name']);
    $sysblock_text = addslashes($_POST['sysblock_text']);
//    print $id;
    if ($id != 0) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_sysblock SET sysblock_name = '" . $sysblock_name . "', sysblock_text = '" . $sysblock_text . "' WHERE id = '" . $id . "'");
    } else {
      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_sysblock (id,sysblock_name,sysblock_text) VALUES ('', '" . $sysblock_name . "', '" . $sysblock_text . "')");
    }
    header("Location:index.php?do=modules&action=modedit&mod=sysblock&moduleaction=1&cp=" . SESSION);
  }

  // Редактирование текстового блока
  function EditBlock($tpl_dir) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_sysblock WHERE id = '" . (int)$_REQUEST['id'] . "'");
    $rows=$sql->fetchrow();
    $GLOBALS['tmpl']->assign("id", $rows->id);
    $GLOBALS['tmpl']->assign("sysblock_name", $rows->sysblock_name);
    $GLOBALS['tmpl']->assign("sysblock_text", $rows->sysblock_text);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_edit.tpl"));
  }

  // Удаление текстового блока
  function DelBlock() {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_sysblock WHERE id = '" . (int)$_REQUEST['id'] . "'");
    header("Location:index.php?do=modules&action=modedit&mod=sysblock&moduleaction=1&cp=" . SESSION);
  }

  // Вывод текстового блока
  function ShowSysBlock($id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_sysblock WHERE id = '" . $id . "'");
    $sysblock = $sql->fetchrow();
    $sql->Close();

    $return = cp_parse_string($sysblock->sysblock_text);
    $return = str_replace("[cp:mediapath]", "/templates/" . T_PATH . "/", $return);

    $sql_modul = $GLOBALS['db']->Query("SELECT ModulPfad,CpEngineTag,CpPHPTag,ModulFunktion,IstFunktion FROM " . PREFIX . "_module WHERE Status = '1'");
    while ($row_modul = $sql_modul->FetchRow()) {
      include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php');
      $return = eregi_replace($row_modul->CpEngineTag, ((($row_modul->IstFunktion == 1) && (function_exists($row_modul->ModulFunktion))) ? $row_modul->CpPHPTag : ''), $return);
    }

    $return = stripslashes(hide($return));

    eval ("?>" . $return . "<?");
  }
}
?>