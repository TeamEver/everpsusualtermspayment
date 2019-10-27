<?php
/**
 * Project : everpsusualtermspayment
 * @author Team Ever <contact@team-ever.com> 
 * @link http://team-ever.com
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class EverPsUsualTermsPayment extends PaymentModule
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

        if (!count(array(Currency::checkPaymentCurrencies($this->id)))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
    }
    
    public function install()
    {
        if (_PS_VERSION_ >= '1.7') {
            $hookPayment = 'paymentOptions';
        } else {
            $hookPayment = 'payment';
        }

        return parent::install()
            && $this->installModuleOrderState()
            && $this->registerHook($hookPayment)
            && $this->registerHook('displayPaymentEU')
            && $this->registerHook('paymentReturn');
    }
    
    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallModuleOrderState();
    }
    
    private function installModuleOrderState()
    {
        $orderState = new OrderState();
        $orderState->name = array();
        $orderState->template = array();
        foreach (Language::getLanguages() as $language) {
            if (Tools::strtolower($language['iso_code'] == 'fr')) {
                $orderState->name[$language['id_lang']] = 'Paiement aux conditions habituelles';
            } else {
                $orderState->name[$language['id_lang']] = 'Usual terms payment';
            }
            $orderState->template[$language['id_lang']] = 'everpsusualtermspayment';
        }
        $orderState->module_name = $this->name;
        $orderState->send_email = false;
        $orderState->color = '#3399ff';
        $orderState->unremovable = false;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = false;
        $orderState->invoice = false;

        if ($orderState->add()) {
            copy(dirname(__FILE__).'/logo.png', _PS_IMG_DIR_.'os/'.$orderState->id.'.gif');
            Configuration::updateValue('PS_OS_EVERPSUSUALTERMSPAYMENT', (int)$orderState->id);
            
            return true;
        } else {
            return false;
        }
    }
    private function uninstallModuleOrderState()
    {
        $sql_state = 'SELECT id_order_state
        FROM `'._DB_PREFIX_.'order_state_lang`
        WHERE template = "'.$this->name.'"';
        $id_state = Db::getInstance()->getValue($sql_state);
        $orderState = new OrderState((int)$id_state);
        if ($orderState->delete()) {
            return true;
        } else {
            return false;
        }
    }
    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->smarty->assign(array(
            'this_path' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
        ));
        
        return $this->display(__FILE__, 'payment.tpl');
    }
    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $payment_options = array(
            'cta_text' => $this->l('Use usual terms payment'),
            'logo' => Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/payment.jpg'),
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true)
        );

        return $payment_options;
    }
    public function hookPaymentReturn($params)
    {
        if (!$this->active) {
            return;
        }

        $state = $params['objOrder']->getCurrentState();
        if (in_array($state, array(Configuration::get('PS_OS_EVERPSUSUALTERMSPAYMENT'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')))) {
            $this->smarty->assign(array(
                'status' => 'ok',
                'id_order' => $params['objOrder']->id,
                'reference_order' => $params['objOrder']->reference
            ));
        } else {
            $this->smarty->assign('status', 'failed');
        }
        return $this->display(__FILE__, 'payment_return.tpl');
    }
    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));
        $currencies_module = $this->getCurrency((int)$cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }
}
