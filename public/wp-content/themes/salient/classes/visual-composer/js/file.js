(function (models) {
  'use strict';

  /**
   * FileSelection Class
   * @constructor
   */
  function FileSelection($el) {
    _.bindAll.apply(this, [this].concat(_.methods(this)));
    this.$el = $el;
    this.currentData = this._getCurrentData();
    this.value = ko.observable();
    this.image = models.medialibrary.newInstance({
      type:            'application/pdf',
      frameTitle:      'Select a pdf',
      frameButtonText: 'Use PDF file',
      attachmentId:    _.lookup(this.currentData, 'attachmentId'),
      attachmentUrl:   _.lookup(this.currentData, 'attachmentUrl'),
      fileName:        _.lookup(this.currentData, 'fileName')
    });
    this.image.attachmentId.subscribe(this.setValue, this);
    this.setValue();
  }

  FileSelection.prototype = {
    constructor: FileSelection,

    /**
     * Set image data as value
     */
    setValue: function () {
      // data to save
      var image = {
        attachmentId:  this.image.attachmentId(),
        attachmentUrl: this.image.attachmentUrl(),
        fileName:      this.image.fileName()
      };

      // set base64 value of objects JSON string
      this.value(btoa(JSON.stringify(image)));
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
        console.log($scriptElement.innerHTML);
        data = atob($scriptElement.innerHTML);
        if (!_.isEmpty(data)) {
          data = JSON.parse(data);
        }
      }
      return data;
    }
  };

  var containers = Array.prototype.slice.call(document.querySelectorAll('.vc-custom-attribute-file'));
  containers.forEach(function (container) {
    var model = new FileSelection(container);
    ko.applyBindings(model, container);
  });

})(parent.happy.models);
