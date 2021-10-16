<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// Если нет валидного кеша (то есть нужно запросить
// данные и сделать валидный кеш)
if ($this->StartResultCache())
	{
		
		$arResult['USR_ID'] = $arParams["TEMPLATE_FOR_USERS"];

	   // Подключить шаблон вывода
	   $this->IncludeComponentTemplate();
	}

?>