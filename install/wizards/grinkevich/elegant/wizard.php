<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");

class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		parent::InitStep();

		$wizard =& $this->GetWizard();
		$wizard->solutionName = "elegant";

		$wizard->SetVar("templateID", "womanizer");
		$this->SetNextStep("site_settings");
	}
}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "elegant";
		parent::InitStep();

		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetTitle(GetMessage("WIZ_STEP_SITE_SET"));
		$this->SetPrevStep("select_site");

		$siteID = $wizard->GetVar("siteID");

		if(COption::GetOptionString("eshop", "wizard_installed", "N", $siteID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
			$this->SetNextStep("data_install");
		else
		{
			if(LANGUAGE_ID != "ru")
				$this->SetNextStep("pay_system");
			else
				$this->SetNextStep("shop_settings");
		}

		$templateID = $wizard->GetVar("templateID");

		$wizard->SetDefaultVars(Array("siteNameSet" => true));

		$wizard->SetDefaultVars(
			Array(
				"siteMetaDescription" => GetMessage("wiz_site_desc"),
				"siteMetaKeywords" => GetMessage("wiz_keywords")
			)
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form">';

		$firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));
		$styleMeta = 'style="display:block"';
		if($firstStep == "Y") $styleMeta = 'style="display:none"';

		$this->content .= '
		<div  id="bx_metadata">
			<div class="wizard-input-form-block">
				<h4 style="margin-top:0"><label for="siteMetaDescription">'.GetMessage("wiz_meta_data").'</label></h4>
				<label for="siteMetaDescription">'.GetMessage("wiz_meta_description").'</label>
				<div class="wizard-input-form-block-content" style="margin-top:7px;">
					<div class="wizard-input-form-field wizard-input-form-field-textarea">'.
						$this->ShowInputField("textarea", "siteMetaDescription", Array("id" => "siteMetaDescription", "style" => "width:100%", "rows"=>"3")).'</div>
				</div>
			</div>';
		$this->content .= '
			<div class="wizard-input-form-block">
				<label for="siteMetaKeywords">'.GetMessage("wiz_meta_keywords").'</label><br>
				<div class="wizard-input-form-block-content" style="margin-top:7px;">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.
						$this->ShowInputField('text', 'siteMetaKeywords', array("id" => "siteMetaKeywords")).'</div>
				</div>
			</div>
		</div>';

		if($firstStep == "Y")
		{
			$this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-input-form-block-content">'.
						$this->ShowCheckboxField(
							"installDemoData",
							"Y",
							(array("id" => "installDemoData", "onClick" => "if(this.checked == true){document.getElementById('bx_metadata').style.display='block';}else{document.getElementById('bx_metadata').style.display='none';}"))
						).
				'
				<label for="installDemoData">'.GetMessage("wiz_structure_data").'</label>
				</div>
			</div>';
			}
		else
		{
			$this->content .= $this->ShowHiddenField("installDemoData","Y");
		}

		if(LANGUAGE_ID != "ru")
		{
			CModule::IncludeModule("catalog");
			$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
			if (!$db_res->Fetch())
			{
				$this->content .= '
				<div class="wizard-input-form-block">
					<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</label></h4>
					<div class="wizard-input-form-block-content">
						'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
						'. $this->ShowCheckboxField("installPriceBASE", "Y",
						(array("id" => "install-demo-data")))
						. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

					</div>
				</div>';
			}
		}

		$this->content .= '</div>';
	}
}

class ShopSettings extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("shop_settings");
		$this->SetTitle(GetMessage("WIZ_STEP_SS"));
		$this->SetNextStep("person_type");
		$this->SetPrevStep("site_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$siteStamp =$wizard->GetPath()."/site/templates/womanizer/images/pechat.gif";
		$siteID = $wizard->GetVar("siteID");

		$wizard->SetDefaultVars(
			Array(
				"shopLocalization" => COption::GetOptionString("eshop", "shopLocalization", "ru", $siteID),
				"shopEmail" => COption::GetOptionString("eshop", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
				"shopOfName" => COption::GetOptionString("eshop", "shopOfName", GetMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
				"shopLocation" => COption::GetOptionString("eshop", "shopLocation", GetMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
				//"shopZip" => 101000,
				"shopAdr" => COption::GetOptionString("eshop", "shopAdr", GetMessage("WIZ_SHOP_ADR_DEF"), $siteID),
				"shopINN" => COption::GetOptionString("eshop", "shopINN", "1234567890", $siteID),
				"shopKPP" => COption::GetOptionString("eshop", "shopKPP", "123456789", $siteID),
				"shopNS" => COption::GetOptionString("eshop", "shopNS", "0000 0000 0000 0000 0000", $siteID),
				"shopBANK" => COption::GetOptionString("eshop", "shopBANK", GetMessage("WIZ_SHOP_BANK_DEF"), $siteID),
				"shopBANKREKV" => COption::GetOptionString("eshop", "shopBANKREKV", GetMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
				"shopKS" => COption::GetOptionString("eshop", "shopKS", "30101 810 4 0000 0000225", $siteID),
				"siteStamp" => COption::GetOptionString("eshop", "siteStamp", $siteStamp, $siteID),

				"installPriceBASE" => COption::GetOptionString("eshop", "installPriceBASE", "Y", $siteID),
			)
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		$siteStamp = $wizard->GetVar("siteStamp", true);

		if (!CModule::IncludeModule("catalog"))
		{
			$this->content .= "<p style='color:red'>".GetMessage("WIZ_NO_MODULE_CATALOG")."</p>";
			$this->SetNextStep("shop_settings");
		}
		else
		{
			$this->content .= '<div class="wizard-input-form">';

			$this->content .= '<div class="wizard-input-form-block">
				<h4><label for="shopEmail">'.GetMessage("WIZ_SHOP_EMAIL").'</label></h4>
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail")).'</div>
				</div>
			</div>';
			$this->content .= '<div class="wizard-input-form-block">
				<h4><label for="shopOfName">'.GetMessage("WIZ_SHOP_OF_NAME").'</label></h4>
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName")).'</div>
				</div>
			</div>';

			$this->content .= '<div class="wizard-input-form-block">
				<h4><label for="shopLocation">'.GetMessage("WIZ_SHOP_LOCATION").'</label></h4>';

			$this->content .= '
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation")).'</div>
				</div>';
			$this->content .= '</div>';

			$this->content .= '
			<div class="wizard-input-form-block">
				<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_ADR").'</label></h4>
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-textarea">'.$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr")).'</div>
				</div>
			</div>';


			$currentLocalization = $wizard->GetVar("shopLocalization");
			if (empty($currentLocalization))
				$currentLocalization = $wizard->GetDefaultVar("shopLocalization");
	 //ru
			$this->content .= '
			<div id="ru_bank_details" class="wizard-input-form-block" >
				<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_BANK_TITLE").'</label></h4>
				<table  class="data-table-no-border" >
					<tr>
						<th width="35%">'.GetMessage("WIZ_SHOP_INN").':</th>
						<td width="65%"><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopINN').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_KPP").':</th>
						<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopKPP').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_NS").':</th>
						<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopNS').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_BANK").':</th>
						<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopBANK').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_BANKREKV").':</th>
						<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopBANKREKV').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_KS").':</th>
						<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopKS').'</div></td>
					</tr>
					<tr>
						<th>'.GetMessage("WIZ_SHOP_STAMP").':</th>
							<td><div class="">'.$this->ShowFileField("siteStamp", Array("show_file_info"=> "N", "id" => "siteStamp", "style" => "width: 90%; border: solid 1px #CECECE; background-color: #F5F5F5; padding: 3px;")).'<br />'.CFile::ShowImage($siteStamp, 75, 75, "border=0 vspace=5", false, false).'</div></td>
						</tr>
				</table>
			</div>
			';


			if (CModule::IncludeModule("catalog"))
			{
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch())
				{
					$this->content .= '
					<div class="wizard-input-form-block">
						<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</label></h4>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
							(array("id" => "install-demo-data")))
							. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

						</div>
					</div>';
				}
			}

			$this->content .= '</div>';

		}
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteStamp", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 70, "max_width" => 190, "make_preview" => "Y"));
	}

}

class PersonType extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("person_type");
		$this->SetTitle(GetMessage("WIZ_STEP_PT"));
		$this->SetNextStep("pay_system");
		$this->SetPrevStep("shop_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();
		$shopLocalization = $wizard->GetVar("shopLocalization", true);

		if ($shopLocalization == "ua")
			$wizard->SetDefaultVars(
				Array(
					"personType" => Array(
						"fiz" => "Y",
						"fiz_ua" => "Y",
						"ur" => "Y",
					)
				)
			);
		else
			$wizard->SetDefaultVars(
				Array(
					"personType" => Array(
						"fiz" => "Y",
						"ur" => "Y",
					)
				)
			);
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$shopLocalization = $wizard->GetVar("shopLocalization", true);

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_PERSON_TYPE_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).' <label for="personTypeF">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).' <label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label><br />';
			//	if ($shopLocalization == "ua")
			//		$this->content .= $this->ShowCheckboxField('personType[fiz_ua]', 'Y', (array("id" => "personTypeFua"))).' <label for="personTypeFua">'.GetMessage("WIZ_PERSON_TYPE_FIZ_UA").'</label><br />';
				$this->content .= '
				</div>
			</div>
			'.GetMessage("WIZ_PERSON_TYPE").'
		</div>';
		$this->content .= '</div>';
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$personType = $wizard->GetVar("personType");

		if (empty($personType["fiz"]) && empty($personType["ur"]))
			$this->SetError(GetMessage('WIZ_NO_PT'));
	}

}

class PaySystem extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("pay_system");
		$this->SetTitle(GetMessage("WIZ_STEP_PS"));
		$this->SetNextStep("data_install");
		if(LANGUAGE_ID != "ru")
			$this->SetPrevStep("site_settings");
		else
		$this->SetPrevStep("person_type");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		if(LANGUAGE_ID == "ru")
		{
				$wizard->SetDefaultVars(
					Array(
						"paysystem" => Array(
							"cash" => "Y",
							"sber" => "Y",
							"bill" => "Y",
						),
						"delivery" => Array(
							"courier" => "Y",
							"self" => "Y",
							"russianpost" => "N",
						)
					)
				);
		}
		else
		{
			$wizard->SetDefaultVars(
				Array(
					"paysystem" => Array(
						"cash" => "Y",
						"paypal" => "Y",
					),
					"delivery" => Array(
						"courier" => "Y",
						"self" => "Y",
						"dhl" => "Y",
						"ups" => "Y",
					)
				)
			);
		}
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$paysystem = $wizard->GetVar("paysystem");

		if (empty($paysystem["cash"]) && empty($paysystem["sber"]) && empty($paysystem["bill"]) && empty($paysystem["paypal"]))
			$this->SetError(GetMessage('WIZ_NO_PS'));
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$shopLocalization = $wizard->GetVar("shopLocalization", true);

		$personType = $wizard->GetVar("personType");

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label><br />';
				if(LANGUAGE_ID == "ru")
				{
				/*	if($shopLocalization == "ua" && ($personType["fiz"] == "Y" || $personType["fiz_ua"] == "Y"))
						$this->content .= $this->ShowCheckboxField('paysystem[oshad]', 'Y', (array("id" => "paysystemO"))).' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_O").'</label><br />';
					else*/if ($personType["fiz"] == "Y")
						$this->content .= $this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label><br />';
					if($personType["ur"] == "Y")
						$this->content .= $this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).' <label for="paysystemB">'.GetMessage("WIZ_PAY_SYSTEM_B").'</label><br />';
				}
				else
				{
					$this->content .= $this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))).' <label for="paysystemP">PayPal</label><br />';
				}
				$this->content .= '</div>
			</div>
			'.GetMessage("WIZ_PAY_SYSTEM").'
		</div>';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_DELIVERY_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label><br />
					'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label><br />';
					if(LANGUAGE_ID == "ru")
					{
						//if ($shopLocalization != "ua")
							$this->content .= $this->ShowCheckboxField('delivery[russianpost]', 'Y', (array("id" => "deliveryR"))).' <label for="deliveryR">'.GetMessage("WIZ_DELIVERY_R").'</label><br />';
					}
					else
					{
						$this->content .= $this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))).' <label for="deliveryD">DHL</label><br />';
						$this->content .= $this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))).' <label for="deliveryU">UPS</label><br />';
					}
					$this->content .= '
				</div>
			</div>
			'.GetMessage("WIZ_DELIVERY").'
		</div>';

		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_LOCATION_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		if(LANGUAGE_ID == "ru")
		{
			$this->content .= $this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
				." <label for=\"loc_ussr\">".GetMessage('WSL_STEP2_GFILE_USSR')."</label><br />";
		}
		$this->content .= $this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
			." <label for=\"loc_usa\">".GetMessage('WSL_STEP2_GFILE_USA')."</label><br />";
		$this->content .= $this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
			." <label for=\"loc_cntr\">".GetMessage('WSL_STEP2_GFILE_CNTR')."</label><br />";
		$this->content .= $this->ShowRadioField("locations_csv", "", array("id" => "none"))
			." <label for=\"none\">".GetMessage('WSL_STEP2_GFILE_NONE')."</label>";
					$this->content .= '
				</div>
			</div>
		</div>';

		$this->content .= '<div class="wizard-input-form-block">'.GetMessage("WIZ_DELIVERY_HINT").'</div>';

		$this->content .= '</div>';
	}
}

class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		if($_SESSION["BX_ESHOP_LOCATION"] == "Y")
			$this->repeatCurrentService = true;
		else
			$this->repeatCurrentService = false;

		$wizard =& $this->GetWizard();
		if($wizard->GetVar("installDemoData") != "Y")
		{
		}
	}
}

class FinishStep extends CFinishWizardStep
{
	function InitStep()
	{
		$this->SetStepID("finish");
		$this->SetNextStep("finish");
		$this->SetTitle(GetMessage("FINISH_STEP_TITLE"));
		$this->SetNextCaption(GetMessage("wiz_go"));
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		if ($wizard->GetVar("proactive") == "Y")
			COption::SetOptionString("statistic", "DEFENCE_ON", "Y");

		$siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
		$rsSites = CSite::GetByID($siteID);
		$siteDir = "/";
		if ($arSite = $rsSites->Fetch())
			$siteDir = $arSite["DIR"];

		$wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));

		$this->CreateNewIndex();

		COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $siteID);

		$this->content .= GetMessage("FINISH_STEP_CONTENT");

		if ($wizard->GetVar("installDemoData") == "Y")
			$this->content .= GetMessage("FINISH_STEP_REINDEX");
	}

}
?>