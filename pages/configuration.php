<?php
/**
 * @var rex_addon $this
 */
$yform = new rex_yform();

$yform->setObjectparams('form_showformafterupdate',1);

$yform->setHiddenField('page', 'cookie_optin/configuration');
$yform->setHiddenField('func', 'save');
$yform->setHiddenField('save', '1');

$yform->setValueField('radio', [
    'cookie_enabled',
    $this->i18n('cookie_status'),
    $this->i18n('cookie_status_off').'=0,'.$this->i18n('cookie_status_simple').'=1,'.$this->i18n('cookie_status_namecheck').'=2,'.$this->i18n('cookie_status_valuecheck').'=3',
    cookie_optin::getStatus()
]);

$yform->setValueField('text', ['cookie_name', $this->i18n('cookie_name'), cookie_optin::getConfig('cookie_name'), 'notice' => '<small>' . $this->i18n('cookie_name_notice') . '</small>']);
$yform->setValueField('text', ['cookie_value', $this->i18n('cookie_value'), cookie_optin::getConfig('cookie_value'), 'notice'=> '<small>'.$this->i18n('cookie_value_notice').'</small>']);

$yform->setActionField('callback', ['cookie_optin::saveConfigForm']);
$yform->setActionField('showtext', [rex_view::success($this->i18n('saved')), '', '', 1]);

$form = $yform->getForm();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $this->i18n('configuration'));
$fragment->setVar('body', $form, false);
echo $fragment->parse('core/page/section.php');
