<?php 
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

// �������� �����
$url = $_SERVER['REQUEST_URI'];
$_REQUEST['urldetectUrl'] = $url;
$_REQUEST['urldetectUrlDelPrint'] = '';

// ========================================================

$flag = 1; // ���� ��������� (������������ ��� ����������)
$temp = ''; // �������� ����������

// ========================================================

// �������� ������ ��� ������
if (strpos($url, '&amp;print=1') || strpos($url, '&print=1') || strpos($url, 'print.html') || strpos($url, '?print=1'))
  {
  $url = strtr($url, array('&amp;print=1'=>'', '&print=1'=>'', 'print.html'=>'', '?print=1'=>''));
  $_REQUEST['urldetectUrlDelPrint'] = $url;
  $_REQUEST['print'] = 1;
  }

// ���� ���� ��������� (index.php?), ���� ������������� � 1
if (strpos($url, '?') || (isset($_REQUEST['module']) && $_REQUEST['module'] != ''))
  { 
  $flag = 0;
  }

if ($flag)
  {
  // ������� �� �������� � ��� ��������
  $url = ereg_replace('([^(-.0-9a-z_\/])', '', $url);

  // �������� index.html, index.htm � index.php
  $url = strtr($url, array('index.html'=>'', 'index.htm'=>'', 'index.php'=>''));

  // ���������� � ����������� ����� �������� /newsarhive/page2.html
  preg_match('/(page|apage|artpage)([0-9]*).html/si', $url, $temp);
  if (isset($temp[2]) && (int)$temp[2] > 0) {
    $_REQUEST[$temp[1]] = (int)$temp[2];
    $url = str_replace($temp[0], '', $url);
    }
  $_REQUEST['urldetectUrlDelPage'] = $url;

//  echo '��� �������������: '.$url.'<br />';

  $sql = "SELECT Id FROM ".PREFIX."_documents WHERE Url = '" .$url."'";

  $sql = $GLOBALS['db']->Query($sql);
  $out = $sql->fetchrow();
  $sql->Close();

  $out = (int) $out->Id;

  if ($out > 0)
    {
    $_REQUEST['id'] = $out;
    $_GET['id'] = $out;
    $_POST['id'] = $out;
    $_REQUEST['urldetectId'] = $out;
    header("HTTP/1.1 200 OK", true);
    }
  else
    {
    $_REQUEST = $_REQUEST;
    }
  }
?>