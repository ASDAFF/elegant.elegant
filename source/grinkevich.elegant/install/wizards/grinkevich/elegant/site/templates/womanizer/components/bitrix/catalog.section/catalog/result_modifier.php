<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$dbSection = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" =>$arResult["ID"]), false, array("UF_BROWSER_TITLE", "UF_TITLE_H1", "UF_KEYWORDS", "UF_META_DESCRIPTION"));
if ($arSection = $dbSection->GetNext())
{
								$arResult["SECTION_USER_FIELDS"]["UF_BROWSER_TITLE"] = $arSection["UF_BROWSER_TITLE"];
								$arResult["SECTION_USER_FIELDS"]["UF_TITLE_H1"] = $arSection["UF_TITLE_H1"];
								$arResult["SECTION_USER_FIELDS"]["UF_KEYWORDS"] = $arSection["UF_KEYWORDS"];
								$arResult["SECTION_USER_FIELDS"]["UF_META_DESCRIPTION"] = $arSection["UF_META_DESCRIPTION"];
}





$basePriceType = CCatalogGroup::GetBaseGroup();
$basePriceTypeName = $basePriceType["NAME"];
/*SKU -- */
$basePriceType = CCatalogGroup::GetBaseGroup();
$basePriceTypeName = $basePriceType["NAME"];

$arOffersIblock = CIBlockPriceTools::GetOffersIBlock($arResult["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffersIblock)? $arOffersIblock["OFFERS_IBLOCK_ID"]: 0;
if ($OFFERS_IBLOCK_ID > 0)
{
	$dbOfferProperties = CIBlock::GetProperties($OFFERS_IBLOCK_ID, Array(), Array("!XML_ID" => "CML2_LINK"));
	$arIblockOfferProps = array();
	$offerPropsExists = false;
	while($arOfferProperties = $dbOfferProperties->Fetch())
	{
		if (!in_array($arOfferProperties["CODE"],$arParams["OFFERS_PROPERTY_CODE"]))
			continue;
		$arIblockOfferProps[] = array("CODE" => $arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$arIblockOfferProps2[] = array("CODE" => "SKU_".$arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
		$offerPropsExists = true;
	}
	$arResult["POPUP_MESS"] = array(
		"addToCart" => GetMessage("CATALOG_ADD_TO_CART"),
		"inCart" => GetMessage("CATALOG_IN_CART"),
		"delayCart" => GetMessage("CATALOG_IN_CART_DELAY"),
		"subscribe" =>  GetMessage("CATALOG_SUBSCRIBE"),
		"inSubscribe" =>  GetMessage("CATALOG_IN_SUBSCRIBE"),
		"notAvailable" =>  GetMessage("CATALOG_NOT_AVAILABLE"),
		"addCompare" => GetMessage("CATALOG_COMPARE"),
		"inCompare" => GetMessage("CATALOG_IN_COMPARE"),
		"chooseProp" => GetMessage("CATALOG_CHOOSE"),
	);
}
/* -- SKU */
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
foreach($arResult["ITEMS"] as $cell=>$arElement)
{
	if($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"] > 0){
		$res = CIBlockElement::GetByID($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
		if($ar_res = $res->GetNext()){
			$arResult["ITEMS"][$cell]["PROPERTIES"]["MANUFACTURER"]["VALUE"] = $ar_res["NAME"];
		}
	}

	$arResult["ITEMS"][$cell]["DELETE_COMPARE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_LIST&id=".$arElement["ID"], array("action", "id")));
	if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) //Product has offers
	{
		$arSku = array();
		$minItemPrice = 0;
		$minItemPriceFormat = "";
		foreach($arElement["OFFERS"] as $arOffer)
		{
			foreach($arOffer["PRICES"] as $code=>$arPrice)
			{
				if($arPrice["CAN_ACCESS"])
				{
					if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])
					{
						$minOfferPrice = $arPrice["DISCOUNT_VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_DISCOUNT_VALUE"];
					}
					else
					{
						$minOfferPrice = $arPrice["VALUE"];
						$minOfferPriceFormat = $arPrice["PRINT_VALUE"];
					}

					if ($minItemPrice > 0 && $minOfferPrice < $minItemPrice)
					{
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
					elseif ($minItemPrice == 0)
					{
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
					}
				}
			}

		}
		if ($minItemPrice > 0)
		{
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE"] = $minItemPrice;
			$arResult["ITEMS"][$cell]["MIN_PRODUCT_OFFER_PRICE_PRINT"] = $minItemPriceFormat;
		}
		if ((!is_array($arIblockOfferProps2) || empty($arIblockOfferProps2)) && is_array($arSku) && !empty($arSku))
			$arIblockOfferProps2[] = array("CODE" => "TITLE", "NAME" => GetMessage("CATALOG_OFFER_NAME"));
		$arResult["ITEMS"][$cell]["SKU_ELEMENTS"] = $arSku;
		$arResult["ITEMS"][$cell]["SKU_PROPERTIES"] = $arIblockOfferProps2;
	}
	else
	{
		$arPrice = $arElement["PRICES"][$basePriceTypeName];
		if($arPrice["CAN_ACCESS"])
		{
			if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])
			{
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE"] = $arPrice["VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_DISCOUNT_PRICE"] = $arPrice["DISCOUNT_VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE_PRINT"] = $arPrice["PRINT_VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_DISCOUNT_PRICE_PRINT"] = $arPrice["PRINT_DISCOUNT_VALUE"];
			}
			else
			{
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE"] = $arPrice["VALUE"];
				$arResult["ITEMS"][$cell]["MIN_PRODUCT_PRICE_PRINT"] = $arPrice["PRINT_VALUE"];
			}
		}
	}
}
/**/

// cache hack to use items list in component_epilog.php
$this->__component->arResult["IDS"] = array();
$this->__component->arResult["DELETE_COMPARE_URLS"] = array();
//$this->__component->arResult["OFFERS_IDS"] = array();

if(isset($arParams["DETAIL_URL"]) && strlen($arParams["DETAIL_URL"]) > 0)
	$urlTemplate = $arParams["DETAIL_URL"];
else
	$urlTemplate = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "DETAIL_PAGE_URL");

//2 Sections subtree
$arSections = array();
$rsSections = CIBlockSection::GetList(
	array(),
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"LEFT_MARGIN" => $arResult["LEFT_MARGIN"],
		"RIGHT_MARGIN" => $arResult["RIGHT_MARGIN"],
	),
	false,
	array("ID", "DEPTH_LEVEL", "SECTION_PAGE_URL")
);

while($arSection = $rsSections->Fetch())
	$arSections[$arSection["ID"]] = $arSection;

foreach ($arResult["ITEMS"] as $key => $arElement){
	$this->__component->arResult["IDS"][] = $arElement["ID"];
	$this->__component->arResult["DELETE_COMPARE_URLS"][$arElement["ID"]] = $arElement["DELETE_COMPARE_URL"];

	/*if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])){
		foreach($arElement["OFFERS"] as $arOffer){
			$this->__component->arResult["OFFERS_IDS"][] = $arOffer["ID"];
		}
	}                 */

	if(is_array($arElement["DETAIL_PICTURE"]))
	{
		$arFilter = '';
		if($arParams["SHARPEN"] != 0)
		{
			$arFilter = array("name" => "sharpen", "precision" => $arParams["SHARPEN"]);
		}
		$arFileTmp = CFile::ResizeImageGet(
			$arElement["DETAIL_PICTURE"],
			array("width" => "140", "height" => "210"),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true, $arFilter
		);

		$arResult["ITEMS"][$key]["PREVIEW_IMG"] = array(
			"SRC" => $arFileTmp["src"],
			'WIDTH' => $arFileTmp["width"],
			'HEIGHT' => $arFileTmp["height"],
		);
	}

	$section_id = $arElement["~IBLOCK_SECTION_ID"];

	if(array_key_exists($section_id, $arSections))
	{
		$urlSection = str_replace(
			array("#SECTION_ID#", "#SECTION_CODE#"),
			array($arSections[$section_id]["ID"], $arSections[$section_id]["CODE"]),
			$urlTemplate
		);

		$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = CIBlock::ReplaceDetailUrl(
			$urlSection,
			$arElement,
			true,
			"E"
		);
	}

	foreach ($arElement["PRICES"] as $k => $v){
		if($v["VALUE_VAT"] != $v["DISCOUNT_VALUE_VAT"]){
			$arResult["ITEMS"][$key]["DISCOUNT"] = intval( 100 - ( ( $v["DISCOUNT_VALUE_VAT"] / $v["VALUE_VAT"] ) * 100 ) );
		}
	}

}

$this->__component->SetResultCacheKeys(array("IDS"));
$this->__component->SetResultCacheKeys(array("DELETE_COMPARE_URLS"));

?>