{extends file="parent:frontend/checkout/ajax_cart.tpl"}

 {block name='frontend_checkout_ajax_cart_button_container'}
    {if count($alsoBoughtArticles) > 0 and $variables['crossSellingLastSeen']}
    
        <div class="cart-cross-selling last-seen-products">
            <div class="last-seen-products--title product--cross-selling--title">
                <span class="title--similar-products">
                    {s name="SPKDetailRecommendationAlsoBoughtLabel" namespace="frontend/detail/index"}Andere Kunden kauften auch{/s}
                </span>
            </div>
            <div class="product--cross-selling--content" data-infiniteSlide=false  >
                 {include file="frontend/_includes/product_slider.tpl" articles=$alsoBoughtArticles sliderItemMinWidth=100 SliderInfiniteSlide=false}
            </div>
        </div>
    {/if}
     {$smarty.block.parent}
{/block}