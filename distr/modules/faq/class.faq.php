<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 1)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: February 22, 2008
::::::::::::::::::::::::::::::::::::::::*/

/*::::::::::::::::::::::::::::::::::::::::
 Module name: Faq
 Short Desc: Frequrent Answer and Questions
 Version: 1.0 alpha
 Authors:  Freeon (php_demon@mail.ru)
 Date: april 5, 2008
::::::::::::::::::::::::::::::::::::::::*/

// Base class of the module
class faq {

  // This function listen category in module
  function faqList($tpl_dir) {
 $faq = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq");
    while ($result = $sql->fetchrow()){
      array_push($faq, $result);
    }
    $GLOBALS['tmpl']->assign("faq", $faq);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_faq_list.tpl"));

  }

  // add new category
  function Addfaq(){
    $faq = htmlspecialchars($_POST['new_faq']);
    $faq_desc = htmlspecialchars($_POST['new_faq_desc']);
    $GLOBALS['db']->Query("INSERT INTO ". PREFIX ."_modul_faq (id,faq_name,description) VALUES ('','$faq', '$faq_desc')");
    header("Location:index.php?do=modules&action=modedit&mod=faq&moduleaction=1&cp=".SESSION);

  }

  // delete category
  function Delfaq(){
    $id = addslashes($_GET['id']);
    $GLOBALS['db']->Query("DELETE FROM ". PREFIX ."_modul_faq WHERE id = '$id'");
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq_quest WHERE parent=$id");
    while ($result = $sql->fetchrow()){
      $GLOBALS['db']->Query("DELETE FROM ". PREFIX ."_modul_faq_quest WHERE parent=$id");
    }
    header("Location:index.php?do=modules&action=modedit&mod=faq&moduleaction=1&cp=".SESSION);
  }

  // update category
  function SaveList(){
    foreach($_POST['faq_name'] as $id => $faq_name) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_faq SET faq_name = '$faq_name' WHERE id = '$id'");
    }
    foreach($_POST['description'] as $id => $description) {
     $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_faq SET description = '$description' WHERE id = '$id'");
    }
    header("Location:index.php?do=modules&action=modedit&mod=faq&moduleaction=1&cp=".SESSION);
  }

  // This function listen questions in category
  function Editfaq($tpl_dir){
    $quest=array();
    $id = intval($_GET['id']);
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq_quest WHERE parent=$id");
    while ($result = $sql->fetchrow()){
      array_push($quest, $result);
    }
    $GLOBALS['tmpl']->assign("quest", $quest);
    $GLOBALS['tmpl']->assign("parent", $id);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_faq_edit.tpl"));
  }

  // save question
  function Savequest() {
      $id = addslashes($_POST['id']);
      $parent = addslashes($_POST['parent']);
      $quest = addslashes($_POST['quest']);
      $answer = addslashes($_POST['answer']);
      print $id;
      if ($id!==''){
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_faq_quest SET quest = '".$quest."', answer = '".$answer."' WHERE id = '$id'"); }
      else if ($id==''){
      $GLOBALS['db']->Query("INSERT INTO ". PREFIX ."_modul_faq_quest (id,quest,answer,parent) VALUES ('','$quest', '$answer','$parent')");  }
      header("Location:index.php?do=modules&action=modedit&mod=faq&moduleaction=edit&cp=".SESSION."&id=".$parent);
  }

  // edit question
  function Edit_quest($tpl_dir) {
    $id = intval($_GET['id']);
    $parent = addslashes($_GET['parent']);
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq_quest WHERE id=$id");
    $rows=$sql->fetchrow();
    if ($parent==''){$parent=$rows->parent;}
    $GLOBALS['tmpl']->assign("parent", $parent);
    $GLOBALS['tmpl']->assign("id", $rows->id);
    $GLOBALS['tmpl']->assign("quest", $rows->quest);
    $GLOBALS['tmpl']->assign("answer", $rows->answer);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_quest_edit.tpl"));
  }

  // delete question
  function Del_quest() {
     $id = addslashes($_GET['id']);
     $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq_quest WHERE id=$id");
     $rows=$sql->fetchrow();
     $GLOBALS['db']->Query("DELETE FROM ". PREFIX ."_modul_faq_quest WHERE id = '$id'");
     header("Location:index.php?do=modules&action=modedit&mod=faq&moduleaction=edit&cp=".SESSION."&id=".$rows->parent);
  }

  // show faq
  function ShowFaq($tpl_dir, $id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq WHERE id='$id'");
    $faq = $sql->fetchrow();
    $quest=array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM ". PREFIX ."_modul_faq_quest WHERE parent=$id");
    while ($result = $sql->fetchrow()){
      array_push($quest, $result);
    }
    $GLOBALS['tmpl']->assign("quest", $quest);
    $GLOBALS['tmpl']->assign("faq_name", $faq->faq_name);
    $GLOBALS['tmpl']->assign("desc", $faq->description);
    $GLOBALS['tmpl']->display($tpl_dir . 'show_faq.tpl');

  }
}
?>