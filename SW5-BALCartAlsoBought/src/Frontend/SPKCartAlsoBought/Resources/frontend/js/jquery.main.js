
(function ($,window) {
    $.subscribe('plugin/swCollapseCart/onLoadCartFinished', function () {
        window.StateManager.updatePlugin('*[data-product-slider="true"]', 'swProductSlider');
    })
    $.subscribe('plugin/swResponsive/onCartRefresh', function () {
        window.StateManager.updatePlugin('*[data-product-slider="true"]', 'swProductSlider');
    })
    $.subscribe('plugin/swProductSlider/onInitInfiniteSlide', function () {
        var me=this;
        me.infiniteSlide=false;
    })
})(jQuery, window);

