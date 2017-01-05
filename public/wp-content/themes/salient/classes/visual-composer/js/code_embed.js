(function () {
  'use strict';

  /**
   * CodeEmbed Class
   *
   * @param {HTMLElement} $el
   * @constructor
   */
  function CodeEmbed($el) {
    _.bindAll.apply(this, [this].concat(_.methods(this)));
    this.$el = $el;
    this.currentData = this._getCurrentData();
    this.value = ko.observable();
    this.code = ko.observable(this.currentData);
    this.code.subscribe(this.saveData, this);
    this.saveData();
  }

  CodeEmbed.prototype = {
    constructor: CodeEmbed,
    $el:         null,
    value:       null,
    code:        null,
    currentData: null,

    /**
     * Save data
     */
    saveData: function () {
      this.value(btoa(this.code()));
    },

    /**
     * Get current data
     *
     * @returns {null|object}
     * @private
     */
    _getCurrentData: function () {
      var data = null;
      var $scriptElement = this.$el.querySelector('script.current-data');
      if ($scriptElement) {
        data = atob($scriptElement.innerHTML);
      }
      return data;
    }
  };

  var containers = Array.prototype.slice.call(document.querySelectorAll('.vc-custom-attribute-code-embed'));
  containers.forEach(function (container) {
    var model = new CodeEmbed(container);
    ko.applyBindings(model, container);
  });

})();
