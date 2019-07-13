<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров");
?>


<?$APPLICATION->IncludeComponent("bitrix:catalog", ".default", array(
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
	"BASKET_URL" => "#SITE_DIR#personal/cart/",
	"ACTION_VARIABLE" => "action",
	"PRODUCT_ID_VARIABLE" => "id",
	"SECTION_ID_VARIABLE" => "SECTION_ID",
	"PRODUCT_QUANTITY_VARIABLE" => "quantity",
	"SEF_MODE" => "Y",
	"SEF_FOLDER" => "#SITE_DIR#catalog/",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "Y",
	"USE_ELEMENT_COUNTER" => "Y",
	"USE_FILTER" => "Y",
	"FILTER_NAME" => "",
	"FILTER_FIELD_CODE" => array(
		0 => "NAME"
	),
	"FILTER_PROPERTY_CODE" => array(
		0 => "MANUFACTURER",
		1 => "MATERIAL",
		2 => "COLOR",
		3 => "SEASON",
		4 => "SIZE",
		5 => "KABLUK",
		6 => "SEX",
		7 => "SPECIALOFFER",
		8 => "NEWPRODUCT"
	),
	"FILTER_PRICE_CODE" => array(
		0 => "BASE",
	),
	"USE_REVIEW" => "Y",
	"MESSAGES_PER_PAGE" => "10",
	"USE_CAPTCHA" => "Y",
	"REVIEW_AJAX_POST" => "Y",
	"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
	"FORUM_ID" => "#FORUM_ID#",
	"URL_TEMPLATES_READ" => "",
	"SHOW_LINK_TO_FORUM" => "N",
	"POST_FIRST_MESSAGE" => "N",
	"USE_COMPARE" => "N",
	"PRICE_CODE" => array(
		0 => "BASE",
	),
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"USE_PRODUCT_QUANTITY" => "Y",
	"CONVERT_CURRENCY" => "Y",
	"CURRENCY_ID" => "RUB",
	"SHOW_TOP_ELEMENTS" => "N",
	"SECTION_COUNT_ELEMENTS" => "N",
	"SECTION_TOP_DEPTH" => "1",
	"PAGE_ELEMENT_COUNT" => "25",
	"LINE_ELEMENT_COUNT" => "1",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"LIST_PROPERTY_CODE" => array(
		0 => "SPECIALOFFER",
		1 => "NEWPRODUCT",
		2 => "SALELEADER"
	),
	"INCLUDE_SUBSECTIONS" => "Y",
	"LIST_META_KEYWORDS" => "-",
	"LIST_META_DESCRIPTION" => "-",
	"LIST_BROWSER_TITLE" => "-",
	"DETAIL_PROPERTY_CODE" => array(
		0 => "ARTNUMBER",
		1 => "MANUFACTURER",
		2 => "MATERIAL",
		3 => "COLOR",
		4 => "SEASON",
		5 => "SIZE",
		6 => "KABLUK",
		7 => "SEX",
		8 => "SPECIALOFFER",
		9 => "NEWPRODUCT",
		10 => "SALELEADER",
		11 => "RECOMMEND",
		12 => "MORE_PHOTO"
	),
	"DETAIL_META_KEYWORDS" => "KEYWORDS",
	"DETAIL_META_DESCRIPTION" => "META_DESCRIPTION",
	"DETAIL_BROWSER_TITLE" => "NAME",
	"LINK_IBLOCK_TYPE" => "",
	"LINK_IBLOCK_ID" => "",
	"LINK_PROPERTY_SID" => "",
	"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
	"USE_ALSO_BUY" => "Y",
	"ALSO_BUY_ELEMENT_COUNT" => "3",
	"ALSO_BUY_MIN_BUYES" => "2",
	"USE_STORE" => "Y",
	"USE_STORE_PHONE" => "Y",
	"USE_STORE_SCHEDULE" => "Y",
	"USE_MIN_AMOUNT" => "Y",
	"MIN_AMOUNT" => "10",
	"STORE_PATH" => "#SITE_DIR#store/#store_id#",
	"MAIN_TITLE" => "Наличие на складах",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "Товары",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "arrows",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
	"PAGER_SHOW_ALL" => "N",
	"PATH_TO_SHIPPING" => "#SITE_DIR#about/delivery/",
	"DISPLAY_IMG_WIDTH" => "180",
	"DISPLAY_IMG_HEIGHT" => "225",
	"DISPLAY_DETAIL_IMG_WIDTH" => "280",
	"DISPLAY_DETAIL_IMG_HEIGHT" => "280",
	"DISPLAY_MORE_PHOTO_WIDTH" => "280",
	"DISPLAY_MORE_PHOTO_HEIGHT" => "280",
	"SHARPEN" => "280",
	"AJAX_OPTION_ADDITIONAL" => "",
	"SEF_URL_TEMPLATES" => array(
		"sections" => "",
		"section" => "#SECTION_CODE#/",
		"element" => "#SECTION_CODE#/#ELEMENT_ID#/",
		"compare" => "compare/",
	)
	),
	false
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>