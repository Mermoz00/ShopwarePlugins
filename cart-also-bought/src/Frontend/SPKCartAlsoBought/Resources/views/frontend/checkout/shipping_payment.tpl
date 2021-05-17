{extends file="parent:frontend/checkout/shipping_payment.tpl"}
{block name='frontend_index_content'}
     {$smarty.block.parent}
    {if count($alsoBoughtArticles) > 0 and $variables["crossSellingInShippingPaymentTemplate"]}
        <div class="cart-cross-selling last-seen-products">
            <div class="last-seen-products--title product--cross-selling--title">
                <span class="title--similar-products">
                    {s name="SPKDetailRecommendationAlsoBoughtLabel" namespace="frontend/detail/index"}Andere Kunden kauften auch{/s}
                </span>
            </div>
            <div class="product--cross-selling--content">

                {include file="frontend/_includes/product_slider.tpl" articles=$alsoBoughtArticles sliderItemMinWidth=200}
            </div>
        </div>
    {/if}
{/block}