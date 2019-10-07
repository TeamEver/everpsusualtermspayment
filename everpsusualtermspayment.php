<?php
/**
 * Prestashop module : everpsusualtermspayment
 *
 * @author Team Ever <contact@team-ever.com>
 * @copyright  Team Ever
 * @license Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class EverPsGAdsTag extends Module
{
    private $html = '';
    private $postErrors = array();
    
    public function __construct()
    {
        $this->name = 'everpsusualtermspayment';
		$this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Team Ever';
        $this->bootstrap = true;
		$this->controllers = array('payment', 'validation');
		$this->is_eu_compatible = 1;

		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

        parent::__construct();
        $this->displayName = $this->l('Ever Ps Usual Terms Payment');
        $this->description = $this->l('This module allows you to use "Usual Terms Payment" for payment method.');
        $this->confirmUninstall = $this->l('Are you sure you want to remove the Usual Terms Payment module?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

		if (!count(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency has been set for this module.');

    }
    
    public function install()
    {
        return parent::install()
            && $this->registerHook('payment');
            && $this->registerHook('displayPaymentEU');
            && $this->registerHook('paymentReturn');
    }
    
    public function uninstall()
    {
        return parent::uninstall();
    }
    
    private function postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {

        }
    }

    private function postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {

        }
        
        $this->html .= $this->displayConfirmation($this->l('Work in Progress'));
    }

    public function getContent()
    {
        $this->html = '';
        if (Tools::isSubmit('btnSubmit')) {
            $this->postValidation();
            if (!count($this->postErrors)) {
                $this->postProcess();
            } else {
                foreach ($this->postErrors as $err) {
                    $this->html .= $this->displayError($err);
                }
            }
        }
        $this->html .= $this->renderForm();
        return $this->html;
    }
    
    public function renderForm()
    {
        $fields_form = array();
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }
    
    public function getConfigFieldsValues()
    {
        return array();
    }
}
