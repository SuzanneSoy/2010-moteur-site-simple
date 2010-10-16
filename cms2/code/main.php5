<?php

function verifications() {
	// url_base doit toujours se terminer par '/'.
	Config::set('url_base', preg_replace("|/*$|", "/", Config::get('url_base'), 1));
}
verifications();

function main() {
	Module::initModules();
	
	Debug("warn", "BDD::reset() est toujours activé, ne pas le garder en production !");
	if (array_key_exists("reset_bdd", $_GET) && $_GET['reset_bdd'] == 'true') {
		BDD::reset();
	}
	
	// TODO : should be $_POST .
	foreach ($_GET as $k => $v) {
		if (substr($k, 0, 4) == 'set_') {
			$k = substr($k, 4);
			$set_uid_page = substr($k, 0, strpos($k, '_'));
			$set_nom_prop = substr($k, strpos($k, '_') + 1);
			$set_page = mPage::page_uid($set_uid_page);
			
			if ($set_page->has_prop($set_nom_prop)) {
				$set_page->$set_nom_prop = $v;
			} else {
				Debug("warn", "Impossible d'effecturer la modification "
				      . "(uid_page = " . htmlspecialchars($set_uid_page)
				      . ", " . htmlspecialchars($set_nom_prop)
				      . " = "  . htmlspecialchars($v) . ").");
			}
		}
	}
	
	// TODO : should be $_POST .
	foreach ($_GET as $k => $v) {
		if (substr($k, 0, 4) == 'act_') {
			$k = substr($k, 4);
			$act_uid_page = substr($k, 0, strpos($k, '_'));
			$act_nom_action = "act_" . substr($k, strpos($k, '_') + 1);
			$act_page = mPage::page_uid($act_uid_page);
			
			if (method_exists($act_page, $act_nom_action)) {
				call_user_func(array($act_page, $act_nom_action), $v);
			} else {
				Debug("warn", "Impossible d'exécuter l'action "
				      . htmlspecialchars($act_nom_action)
				      . " (uid_page = " . htmlspecialchars($act_uid_page) . ").");
			}
		}
	}
	
	$res = array_key_exists('res', $_GET) ? $_GET['res'] : null;
	if (array_key_exists('uid_page', $_GET)) {
		$page = mPage::page_uid($_GET['uid_page']);
	} else {
		$page = mPage::page_systeme('racine');
	}
	
	$rendu = $page->rendu($res);
	BDD::close();

	$rendu->erreurs()->litteral(_Debug::afficher());
	$rendu = $rendu->to_XHTML_5();

	echo $rendu;
	// echo "<pre>" . htmlspecialchars($rendu) . "</pre>";
}

?>