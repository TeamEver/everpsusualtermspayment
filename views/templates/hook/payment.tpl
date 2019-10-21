{*
 * Project : everpsusualtermspayment
 * @author Team Ever <contact@team-ever.com> 
 * @link http://team-ever.com
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
*}

<p class="payment_module">
	<a href="{$link->getModuleLink('everpsusualtermspayment', 'payment', [], true)|escape:'html'}" title="{l s='Pay using usual terms' mod='everpsusualtermspayment'}">
		<img src="{$this_path}payment.jpg" alt="{l s='Pay using usual terms' mod='everpsusualtermspayment'}" width="86" height="49" />
		{l s='Pay using usual terms' mod='everpsusualtermspayment'} {l s='(order processing will be longer)' mod='everpsusualtermspayment'}
	</a>
</p>
