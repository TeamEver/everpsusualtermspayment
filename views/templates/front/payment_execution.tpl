{*
 * Project : everpsusualtermspayment
 * @author Team Ever <contact@team-ever.com> 
 * @link http://team-ever.com
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
*}

{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='everpsusualtermspayment'}">{l s='Checkout' mod='everpsusualtermspayment'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Usual terms payment' mod='everpsusualtermspayment'}
{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='everpsusualtermspayment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='everpsusualtermspayment'}</p>
{else}

<h3>{l s='Usual terms payment' mod='everpsusualtermspayment'}</h3>
<form action="{$link->getModuleLink('everpsusualtermspayment', 'validation', [], true)|escape:'html'}" method="post">
	<p>
		<img src="{$this_path}payment.jpg" alt="{l s='Usual terms' mod='everpsusualtermspayment'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='You have chosen to pay with usual terms.' mod='everpsusualtermspayment'}
		<br/><br />
	</p>
	<p style="margin-top:20px;">
		- {l s='The total amount of your order comes to:' mod='everpsusualtermspayment'}
		<span id="amount" class="price">{displayPrice price=$total}</span>
		{if $use_taxes == 1}
			{l s='(tax incl.)' mod='everpsusualtermspayment'}
		{/if}
	</p>
	<p>
		<b>{l s='Please confirm your order by clicking "I confirm my order".' mod='everpsusualtermspayment'}</b>
	</p>
	<p class="cart_navigation" id="cart_navigation">
		<input type="submit" value="{l s='I confirm my order' mod='everpsusualtermspayment'}" class="exclusive_large"/>
		<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Other payment methods' mod='everpsusualtermspayment'}</a>
	</p>
</form>
{/if}
