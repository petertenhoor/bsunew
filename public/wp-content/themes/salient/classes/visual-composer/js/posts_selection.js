(function () {
  'use strict';

  /**
   * PostsSelection Class
   *
   * @param {HTMLElement} $el
   * @constructor
   */
  function PostsSelection($el) {
    _.bindAll.apply(this, [this].concat(_.methods(this)));
    this.$el = $el;
    this.value = ko.observable();
    this.allPosts = this._getAllPosts();
    this.currentData = this._getCurrentData();
    this.selectionMethod = ko.observable(_.lookup(this.currentData || {}, 'selectionMethod'));
    this.numberOfPosts = ko.observable(_.lookup(this.currentData || {}, 'numberOfPosts'));
    this.maximumColumns = ko.observable(_.lookup(this.currentData || {}, 'maximumColumns'));

    // get current posts from current data
    var currentPosts = _.chain(_.lookup(this.currentData || [], 'manualPosts'))
      .map(_.bind(function (postId) {
        return this._getPostById(postId);
      }, this))
      .filter(function (post) {
        return _.isObject(post);
      })
      .value();

    this.manualPosts = ko.observableArray(currentPosts);
    this.manualPostsSelection = ko.computed(this._notSelectedPosts, this);
    this.currentPostSelection = ko.observable();

    // set value when properties changes
    this.selectionMethod.subscribe(this.setValue, this);
    this.manualPosts.subscribe(this.setValue, this);
    this.numberOfPosts.subscribe(this.setValue, this);
    this.maximumColumns.subscribe(this.setValue, this);
    this.setValue();
  }

  PostsSelection.prototype = {
    constructor:          PostsSelection,
    $el:                  null,
    value:                null,
    allPosts:             null,
    currentData:          null,
    selectionMethod:      null,
    numberOfPosts:        null,
    maximumColumns:       null,
    manualPosts:          null,
    manualPostsSelection: null,
    currentPostSelection: null,

    /**
     * Save data
     */
    setValue: function () {
      var obj = {
        selectionMethod: this.selectionMethod(),
        numberOfPosts:   this.numberOfPosts(),
        maximumColumns:  this.maximumColumns(),
        manualPosts:     _.map(this.manualPosts(), function (post) {
          return post.ID;
        })
      };

      this.value(btoa(JSON.stringify(obj)));
    },

    /**
     * Get all posts as JSON string from script element .all-posts
     *
     * @returns {Array}
     * @private
     */
    _getAllPosts: function () {
      var posts          = [],
          $scriptElement = this.$el.querySelector('script.all-posts');

      if ($scriptElement instanceof HTMLScriptElement) {
        try {
          posts = JSON.parse($scriptElement.innerHTML);
        } catch (err) {
          throw new Error('Can not parse json: ' + err.message);
        }
      }

      return posts;
    },

    /**
     * Get current data as Base64 encoded string from script element .current-data
     *
     * @returns {*}
     * @private
     */
    _getCurrentData: function () {
      var data           = null,
          $scriptElement = this.$el.querySelector('script.current-data');

      if ($scriptElement instanceof HTMLScriptElement) {
        var base64Data = atob($scriptElement.innerHTML);
        if (!_.isEmpty(base64Data)) {
          data = JSON.parse(base64Data);
        }
      }

      return data;
    },

    /**
     * Get not selected posts
     *
     * @returns {Array}
     * @private
     */
    _notSelectedPosts: function () {
      var selectedPostIds = _.map(this.manualPosts(), function (post) {
        return post.ID;
      });
      var notSelectedPosts = _.filter(this.allPosts, function (post) {
        return _.indexOf(selectedPostIds, post.ID) === -1;
      });

      return notSelectedPosts;
    },

    /**
     * Get post by id
     * @param id
     * @returns {Array}
     * @private
     */
    _getPostById: function (id) {
      return _.find(this.allPosts, function (post) {
        return post.ID === id;
      });
    },

    /**
     * Add post current post selection
     */
    addPost: function () {
      var post = this._getPostById(this.currentPostSelection());
      if (_.isObject(post)) {
        this.manualPosts.push(post)
      }
    },

    /**
     * Remove post
     * @param {object} post
     */
    removePost: function (post) {
      this.manualPosts.remove(post);
    }
  };

  var containers = Array.prototype.slice.call(document.querySelectorAll('.vc-custom-attribute-posts-selection'));
  containers.forEach(function (container) {
    var model = new PostsSelection(container);
    ko.applyBindings(model, container);
  });

})();
