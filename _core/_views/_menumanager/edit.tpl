{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование меню "{$menu->getName()}"</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_menumanager/form.tpl"}
{/block}

{block name="asu_right"}

{/block}