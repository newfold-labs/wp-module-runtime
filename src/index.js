import { addQueryArgs } from "@wordpress/url";
export const NewfoldRuntime = {
  /**
   *
   * @param {"hasEcomdash" | "hasYithExtended" | "isEcommerce" | "isJarvis"} name Capability to check
   * @returns {boolean}
   */
  hasCapability(name) {
    return window.NewfoldRuntime?.capabilities[name] === true;
  },
  adminUrl(path) {
    return window.NewfoldRuntime?.admin_url + path;
  },
  /**
   *
   * @param {string} url API Endpoint
   * @param {Record<string, any>} qs Query parameters
   *
   * @returns {string}
   */
  createApiUrl(url, qs = {}) {
    return addQueryArgs(window.NewfoldRuntime?.rest_url, {
      rest_route: url,
      ...qs,
    });
  },
  get sdk() {
    return window.NewfoldRuntime?.sdk;
  },
};
