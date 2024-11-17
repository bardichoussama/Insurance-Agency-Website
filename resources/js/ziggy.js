// resources/js/ziggy.js
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  // If Ziggy is available on the window object (from @routes), use it.
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}

// Export ZiggyVue so it can be used in Vue components
export const ZiggyVue = window.Ziggy;
