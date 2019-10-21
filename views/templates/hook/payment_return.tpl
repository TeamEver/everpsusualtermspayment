{*
 * Project : everpsusualtermspayment
 * @author Team Ever <contact@team-ever.com> 
 * @link http://team-ever.com
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
*}

{if $status == 'ok'}
<p>{l s='Your order %s on %s is complete.' sprintf=array($reference_order,$shop_name) mod='everpsusualtermspayment'} 
		<br /><br />
		{l s='An administrator will contact you shortly' mod='everpsusualtermspayment'}
		<br /><br /><strong>{l s='Your order will be sent as soon as we receive your payment.' mod='everpsusualtermspayment'}</strong>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='everpsusualtermspayment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='everpsusualtermspayment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We have noticed that there is a problem with your order. If you think this is an error, you can contact our' mod='everpsusualtermspayment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='everpsusualtermspayment'}</a>.
	</p>
{/if}
