<?php
/**
 * @var rex_addon $this
 */

$showList = true;
$data_id = rex_request('data_id', 'int', 0);
$func = rex_request('func', 'string');

if($func != '') {
    $yform = new rex_yform();

    $yform->setHiddenField('page', 'cookie_optin/cookies');
    $yform->setHiddenField('func', $func);
    $yform->setHiddenField('save', 1);

    $yform->setObjectparams('main_table', rex::getTable('cookie_optin_cookies'));
    $yform->setObjectparams('form_name', 'cookie_optin_form');

    $yform->setValueField('text', ['name', $this->i18n('field_name')]);
    $yform->setValueField('text', ['provider', $this->i18n('field_provider')]);
    $yform->setValueField('textarea', ['usage', $this->i18n('field_usage')]);
    $yform->setValueField('text', ['expiry', $this->i18n('field_expiry')]);
    $yform->setValueField('text', ['type', $this->i18n('field_type')]);

    if($func == 'add') {
        $yform->setObjectparams('submit_btn_label', rex_i18n::msg('add'));
        $title = $this->i18n('cookie_add');
        $yform->setActionField('db', [rex::getTable('cookie_optin_cookies')]);
        $form = $yform->getForm();

        if($yform->objparams['actions_executed']) {
            echo rex_view::success($this->i18n('cookie_added'));
        } else {
            $showList = false;

            $fragment = new rex_fragment();
            $fragment->setVar('class', 'edit', false);
            $fragment->setVar('body', $form, false);
            $fragment->setVar('title', $this->i18n('cookie_add'));
            echo $fragment->parse('core/page/section.php');
        }

    } elseif($func == 'edit') {
        $yform->setObjectparams('submit_btn_label', rex_i18n::msg('save'));
        $yform->setHiddenField('data_id', $data_id);
        $yform->setActionField('db', [rex::getTable('cookie_optin_cookies'), 'id='.$data_id]);
        $yform->setObjectparams('main_id', $data_id);
        $yform->setObjectparams('main_where', 'id='.$data_id);
        $yform->setObjectparams('getdata', true);
        $form = $yform->getForm();

        if($yform->objparams['actions_executed']) {
            echo rex_view::success($this->i18n('cookie_updated'));
        } else {
            $showList = false;

            $fragment = new rex_fragment();
            $fragment->setVar('class', 'edit', false);
            $fragment->setVar('body', $form, false);
            $fragment->setVar('title', $this->i18n('cookie_edit'));
            echo $fragment->parse('core/page/section.php');
        }
    }
}
if($showList === true) {
    $list = rex_list::factory('Select * from '.rex::getTable('cookie_optin_cookies'));

    $list->setColumnFormat('id', 'Id');
    $list->addParam('page', 'cookie_optin/cookies');

    $tdIcon = '<i class="fa fa-edit"></i>';
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '"' . rex::getAccesskey($this->i18n('add_cookie'), 'add') . '><i class="rex-icon rex-icon-add"></i></a>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'data_id' => '###id###']);

    $list->setColumnParams('id', ['data_id' => '###id###', 'func' => 'edit']);
    $list->setColumnSortable('id');

    $list->removeColumn('id');

    $list->setColumnLabel('name', $this->i18n('field_name'));
    $list->setColumnLabel('provider', $this->i18n('field_provider'));
    $list->setColumnLabel('usage', $this->i18n('field_usage'));
    $list->setColumnLabel('expiry', $this->i18n('field_expiry'));
    $list->setColumnLabel('type', $this->i18n('field_type'));

    $list->removeColumn('id');

    $list->show();
}