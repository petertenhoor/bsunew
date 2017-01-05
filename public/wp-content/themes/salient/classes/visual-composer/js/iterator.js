(function ($) {
  'use strict';

  /**
   * Iterator Class
   *
   * @param {HTMLElement} $el
   * @constructor
   */
  function Iterator($el) {
    _.bindAll.apply(this, [this].concat(_.methods(this)));
    this.$el = $el;
    this.loading = ko.observable(false);
    this.items = ko.observableArray();
    this.singleItemData = this._getNewSingleItemData();
    this.currentData = this._getCurrentData();
    this.value = ko.observable();
    this.itemsCount = 0;

    // set current data
    this._initializeItems(this.currentData);

    // attach event on submit
    var btn = this._getSubmitButton();
    if (btn) {
      btn.addEventListener('click', this.saveData.bind(this));
    }
  }

  Iterator.prototype = {
    constructor:    Iterator,
    $el:            null,
    singleItemData: null,
    loading:        null,
    items:          null,
    itemsCount:     0,

    /**
     * Save data as base64 encoded string of data json object
     */
    saveData: function () {
      this.value(btoa(this._getJSON()));
    },

    /**
     * Initialize items
     *
     * @param {Array} data
     * @private
     */
    _initializeItems: function (data) {
      var self = this;
      var getResult = function () {
        return new Promise(function (resolve, reject) {
          var timeout     = setTimeout(reject, 2000),
              itemsResult = [];

          if (data.length === 0) {
            resolve(itemsResult);
          } else {
            data.forEach(function (item) {
              self.add(item, true).then(function (result) {
                itemsResult.push(result);
                if (itemsResult.length === data.length) {
                  resolve(itemsResult);
                  clearTimeout(timeout);
                }
              });
            });
          }
        });
      };

      getResult().then(function (results) {
        // sort by index value
        results = _.sortBy(results, function (res) {
          return res.index;
        });

        // insert
        results.forEach(function (res) {
          self.items.push({html: res.html});
          self._initTinyMce(res.html);
        });
      });
    },

    /**
     * Get submit button
     *
     * @returns {null|HTMLElement}
     * @private
     */
    _getSubmitButton: function () {
      var container, parent, btn;
      while (!container || !parent || parent.tagName.toLowerCase() !== 'body') {
        parent = parent ? parent.parentNode : this.$el.parentNode;
        if (parent.classList.contains('vc_ui-panel-content-container')) {
          container = parent;
        }
      }
      if (container) {
        btn = container.parentNode.querySelector('.vc_ui-panel-footer-container *[data-vc-ui-element="button-save"]');
      }

      return btn;
    },

    /**
     * Get type of element
     *
     * @param {HTMLElement} element
     * @returns {string}
     * @private
     */
    _getTypeOfElement: function (element) {
      var type;
      for (var i = 0; i < this.singleItemData.length; i++) {
        var item = this.singleItemData[i];
        if (element.classList.contains(item.type)) {
          type = item.type;
          break;
        }
      }

      return type;
    },

    /**
     * Get current data
     *
     * @returns {Array}
     * @private
     */
    _getCurrentData: function () {
      var $scriptElement = this.$el.querySelector('.item-current-data');

      // get data from script element content
      // first decode base64 string and then decode JSON string
      // prevent breaking script in a try-catch statement
      var data = [];
      if ($scriptElement) {
        try {
          data = JSON.parse(atob($scriptElement.innerHTML));
        } catch (err) {
          data = [];
        }
      }

      // re-parse json object when first array element is a string
      // this can occur when quotes are escaped
      if (_.isArray(data) && data.length > 0 && _.isString(data[0])) {
        data = JSON.parse(data);
      }

      // filter out null values
      data = data.filter(function (a) {return a !== null});

      // group by index
      data = _.chain(data).groupBy('index').toArray().value();

      return data;
    },

    /**
     * Get single item data
     *
     * @returns {null|array}
     * @private
     */
    _getNewSingleItemData: function () {
      var scriptElement = this.$el.querySelector('script.item-block-data'),
          data          = scriptElement ? JSON.parse(scriptElement.innerHTML) : null;

      // search for first element property `custom_param_value`
      if (_.isArray(data)) {
        data = data[0];
        if (_.isObject(data) && data.hasOwnProperty('custom_param_value')) {
          data = data.custom_param_value;
        }
      }

      return data;
    },

    /**
     * Get new fields item
     *
     * @param {Array}     itemData
     * @returns {Promise}
     * @private
     */
    _requestSingleItem: function (itemData) {
      return new Promise(_.bind(function (resolve, reject) {
        var index = this.itemsCount > 0 ? this.itemsCount - 1 : 0;
        $.getJSON(ajaxurl, {action: 'getIteratorFieldsItem', items: itemData, index: index})
          .success(resolve.bind(this))
          .error(reject.bind(this));
      }, this));
    },

    /**
     * Get JSON string of current value
     *
     * @returns {string}
     * @private
     */
    _getJSON: function () {
      var fields = Array.prototype.slice.call(this.$el.querySelectorAll('.items *[name]'));
      var a = fields
        .map(_.bind(function (i) {
          return {
            name:  i.name,
            value: tinyMCE.get(i.name) ? tinyMCE.get(i.name).getContent() : i.value,
            index: i.name.match(/blocks_([\d]+)/) ? i.name.match(/blocks_([\d]+)/)[1] : null,
            type:  this._getTypeOfElement(i)
          };
        }, this));

      return JSON.stringify(a);
    },

    /**
     * Create single item data
     * A single item is a new row iterator item
     *
     * @param {Array|null} items
     * @private
     */
    _createSingleItemData: function (items) {
      items = items || [];
      var cleanSingleItemData = this._getNewSingleItemData(),
          newSingleItemData;

      for (var i = 0; i < items.length; i++) {
        var storedData = items[i],
            newData    = cleanSingleItemData.length - 1 >= i ? cleanSingleItemData[i] : null;

        if (storedData && newData) {
          newData.value = storedData.value;
        }
      }

      return newSingleItemData || cleanSingleItemData;
    },

    /**
     * Initialize tinymce on given html
     * Html should be already injected in the DOM in order to bind tinymce
     *
     * @param {string} html
     * @private
     */
    _initTinyMce: function (html) {
      var self = this;
      if (html.match(/tinymce/)) {
        html.replace(/<textarea.*id="([^"]+)".*>/g, function (el, id) {
          var $textarea = self.$el.querySelector('#' + id),
              $input    = self.$el.querySelector('input[name="' + $textarea.id.replace(/^wpb_tinymce_/, '') + '"]'),
              content   = $input ? $input.value : '';

          if ($textarea) {
            // init tinymce
            init_textarea_html($($textarea));

            // set tinymce content
            var tinymceInstance = tinyMCE.get($textarea.name);
            if (tinymceInstance) {
              tinymceInstance.setContent(content);
            }
          }
        });
      }
    },

    /**
     * Add single item
     *
     * @param {Array} itemData
     * @param {boolean} bypassInsert
     */
    add: function (itemData, bypassInsert) {
      var self = this;
      this.itemsCount++;
      itemData = this._createSingleItemData(itemData);
      this.loading(true);

      return new Promise(function (resolve, reject) {
        var index = self.itemsCount;
        self._requestSingleItem(itemData)
          .then(function (fields) {
            var str = fields.join('');
            if (bypassInsert !== true) {
              self.items.push({html: str});
            }

            if (bypassInsert !== true) {
              self._initTinyMce(str);
            }

            resolve({
              index: index,
              html:  str
            });
            self.loading(false);
          })
          .catch(function (err) {
            reject(err);
            self.loading(false);
          });
      });
    },

    /**
     * Remove item
     *
     * @param {object} obj
     */
    remove: function (obj) {
      this.itemsCount--;
      this.items.remove(obj);
    }
  };

  var containers = Array.prototype.slice.call(document.querySelectorAll('.vc-custom-attribute-iterator'));
  containers.forEach(function (container) {
    var model = new Iterator(container);
    ko.applyBindings(model, container);
  });

})(jQuery);
