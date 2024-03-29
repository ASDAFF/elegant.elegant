<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cart-items" id="id-subscribe-list" style="display:none;">
	<div class="sort">
		<div class="sorttext"><?=GetMessage("SALE_PRD_IN_BASKET")?></div>
		<a href="javascript:void(0)" onclick="ShowBasketItems(1);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_ACT")?> (<?=count($arResult["ITEMS"]["AnDelCanBuy"])?>)</a>
		<?if ($countItemsDelay=count($arResult["ITEMS"]["DelDelCanBuy"])):?><a href="javascript:void(0)" onclick="ShowBasketItems(2);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_SHELVE")?> (<?=$countItemsDelay?>)</a><?endif?>
		<a href="javascript:void(0)" class="sortbutton current"><?=GetMessage("SALE_PRD_IN_BASKET_SUBSCRIBE")?></a>
		<?if ($countItemsNotAvailable=count($arResult["ITEMS"]["nAnCanBuy"])):?><a href="javascript:void(0)" onclick="ShowBasketItems(4);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_NOTA")?> (<?=$countItemsNotAvailable?>)</a><?endif?>
	</div>
	<?if(count($arResult["ITEMS"]["ProdSubscribe"]) > 0):?>
	<table class="equipment mycurrentorders" rules="rows" style="width:726px">
	<thead>
		<tr>
			<?if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_NAME")?></td>
				<td></td>
			<?endif;?>
			<?if (in_array("VAT", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_VAT")?></td>
			<?endif;?>
			<?if (in_array("TYPE", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_PRICE_TYPE")?></td>
			<?endif;?>
			<?if (in_array("DISCOUNT", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_DISCOUNT")?></td>
			<?endif;?>
			<?if (in_array("WEIGHT", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_WEIGHT")?></td>
			<?endif;?>
			<?if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_QUANTITY")?></td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td><?= GetMessage("SALE_PRICE")?></td>
			<?endif;?>
				<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
	<?
	foreach($arResult["ITEMS"]["ProdSubscribe"] as $arBasketItems)
	{
		?>
		<tr>
			<td align="center">
				<?if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
					<a class="deleteitem" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" onclick="return DeleteFromCart(this);" title="<?=GetMessage("SALE_DELETE_PRD")?>"></a>
				<?endif;?>
				<?if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
					<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>">
				<?endif;?>
				<?if (strlen($arBasketItems["DETAIL_PICTURE"]["SRC"]) > 0) :?>
					<img src="<?=$arBasketItems["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arBasketItems["NAME"] ?>"/>
				<?else:?>
					<img src="/bitrix/components/bitrix/eshop.sale.basket.basket/templates/.default/images/no-photo.png" alt="<?=$arBasketItems["NAME"] ?>"/>
				<?endif?>
				<?if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
					</a>
				<?endif;?>
			</td>
			<?if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td>
				<?if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
					<a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>">
				<?endif;?>
					<?=$arBasketItems["NAME"] ?>
				<?if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
					</a>
				<?endif;?>
				<?if (in_array("PROPS", $arParams["COLUMNS_LIST"]))
				{
					foreach($arBasketItems["PROPS"] as $val)
					{
						echo "<br />".$val["NAME"].": ".$val["VALUE"];
					}
				}?>
				</td>
			<?endif;?>

			<?if (in_array("VAT", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-price"><?=$arBasketItems["VAT_RATE_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("TYPE", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-type"><?=$arBasketItems["NOTES"]?></td>
			<?endif;?>
			<?if (in_array("DISCOUNT", $arParams["COLUMNS_LIST"])):?>
				<td><?=(strlen($arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"]) > 0 ? $arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"] : "���" )?></td>
			<?endif;?>
			<?if (in_array("WEIGHT", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-weight"><?=$arBasketItems["WEIGHT_FORMATED"]?></td>
			<?endif;?>
			<?if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-quantity"><?=$arBasketItems["QUANTITY"]?></td>
			<?endif;?>
			<?if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td class="cart-item-price">
					<?if(doubleval($arBasketItems["FULL_PRICE"]) > 0):?>
						<span class="basketprice"><?=$arBasketItems["PRICE_FORMATED"]?></span><br />
						<span style="text-decoration:line-through;"><?=$arBasketItems["FULL_PRICE_FORMATED"]?></span>
					<?else:?>
						<span class="basketprice"><?=$arBasketItems["PRICE_FORMATED"];?></span>
					<?endif?>
				</td>
			<?endif;?>
				<td><a href="/personal/cart/?action=delete&id=<?=$arBasketItems["ID"]?>" id="delBasketProd" data-id="<?=$arBasketItems["PRODUCT_ID"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/del_basket.gif" alt="" /></a>&nbsp;</td>
		</tr>
		<?
	}
	?>
	</tbody>
</table>
<?endif;?>
</div>
<?