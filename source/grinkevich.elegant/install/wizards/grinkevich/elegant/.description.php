<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!defined("WIZARD_DEFAULT_SITE_ID") && !empty($_REQUEST["wizardSiteID"]))
	define("WIZARD_DEFAULT_SITE_ID", $_REQUEST["wizardSiteID"]);

$arWizardDescription = Array(
	"NAME" => GetMessage("PORTAL_WIZARD_NAME"),
	"DESCRIPTION" => GetMessage("PORTAL_WIZARD_DESC"),
	"VERSION" => "1.0.18",
	"START_TYPE" => "WINDOW",
	"WIZARD_TYPE" => "INSTALL",
	"IMAGE" => "/images/".LANGUAGE_ID."/solution.png",
	"PARENT" => "wizard_sol",
	"TEMPLATES" => Array(
		Array("SCRIPT" => "wizard_sol")
	),
	"STEPS" => (defined("WIZARD_DEFAULT_SITE_ID") ?
		Array("SiteSettingsStep", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep", "FinishStep") :
		Array("SelectSiteStep", "SiteSettingsStep", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep", "FinishStep"))
);
?>