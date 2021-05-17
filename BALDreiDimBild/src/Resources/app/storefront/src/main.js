// src/Resources/app/storefront/src/main.js

// import all necessary storefront plugins
import ThreePlugin from './three-plugin/three-plugin.plugin';

// register them via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('ThreePlugin', ThreePlugin, '[data-three-plugin]');
