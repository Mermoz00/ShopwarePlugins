// src/Resources/app/storefront/src/main.js

// import all necessary storefront plugins
import HidePricePlugin from './js/main.plugin';

// register them via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('HidePricePlugin', HidePricePlugin, '[data-hide-price-plugin]');
