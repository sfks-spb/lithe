(function () {
    'use strict';

    var Html = {
      tag: function(classname) {
        return Html.node.classList.add(classname);
      },
      untag: function(classname) {
        return Html.node.classList.remove(classname);
      },
      has: function(classname) {
        return Html.node.classList.contains(classname);
      }
    };

    Html.node = document.html || document.getElementsByTagName('html')[0];

    var Features = {
      passiveListener: () => {
        var passiveSupported;
        passiveSupported = false;
        try {
          window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
            get: function() {
              return passiveSupported = true;
            }
          }));
        } catch (error) {
        }
        return passiveSupported;
      }
    };

    var VkGroups = class VkGroups {
      constructor(selector, options) {
        this.init = this.init.bind(this);
        this.processMutations = this.processMutations.bind(this);
        this.changeTheme = this.changeTheme.bind(this);
        this.changeSize = this.changeSize.bind(this);
        this.selector = selector;
        this.options = Object.assign(VkGroups.defaults, options || {});
        if (window.lithe) {
          this.changeTheme({
            detail: window.lithe.theme
          });
        }
        document.addEventListener("DOMContentLoaded", this.init, false);
        document.addEventListener("ThemeChanged", this.changeTheme, false);
      }

      init() {
        var container, j, k, len, len1, observer, ref, ref1, sidebar;
        this.sidebars = document.querySelectorAll('.widget-area');
        if (this.sidebars) {
          ref = this.sidebars;
          for (j = 0, len = ref.length; j < len; j++) {
            sidebar = ref[j];
            sidebar.dataset.sidebarWidth = sidebar.clientWidth;
          }
          observer = new MutationObserver(this.processMutations);
          this.containers = document.querySelectorAll('.vk-group');
          ref1 = this.containers;
          for (k = 0, len1 = ref1.length; k < len1; k++) {
            container = ref1[k];
            observer.observe(container, {
              childList: true,
              attributes: false,
              subtree: false
            });
          }
          this.reloadAll();
          return window.addEventListener("resize", this.changeSize, Features.passiveListener ? {
            passive: true
          } : false);
        }
      }

      processMutations(mutations) {
        var j, k, len, len1, mutation, node, ref;
        for (j = 0, len = mutations.length; j < len; j++) {
          mutation = mutations[j];
          if (mutation.type === "childList") {
            ref = mutation.addedNodes;
            for (k = 0, len1 = ref.length; k < len1; k++) {
              node = ref[k];
              if (node instanceof HTMLElement) {
                node.addEventListener("load", () => {
                  return node.closest('.sidebar-widget').classList.add('vk-widget-loaded');
                });
              }
            }
          }
        }
      }

      changeTheme(event) {
        var i, j, len, ref, theme;
        theme = event.detail;
        if (VkGroups.themes[theme]) {
          ref = VkGroups.themes[theme];
          for (i = j = 0, len = ref.length; j < len; i = ++j) {
            this.options["color" + (i + 1)] = VkGroups.themes[theme][i];
          }
          return this.reloadAll();
        } else {
          return console.warn('VkGroups: theme "' + this.theme + '" not defined');
        }
      }

      changeSize() {
        if (this.sidebars) {
          if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
          }
          return this.resizeTimeout = setTimeout(() => {
            var j, len, ref, sidebar;
            ref = this.sidebars;
            for (j = 0, len = ref.length; j < len; j++) {
              sidebar = ref[j];
              if (sidebar.clientWidth !== Number(sidebar.dataset.sidebarWidth)) {
                this.reloadSidebar(sidebar);
              }
            }
          }, 300);
        }
      }

      reloadAll() {
        var container, j, len, ref, results;
        if (this.containers) {
          ref = this.containers;
          results = [];
          for (j = 0, len = ref.length; j < len; j++) {
            container = ref[j];
            results.push(this.reload(container));
          }
          return results;
        }
      }

      reloadSidebar(sidebar) {
        var container, containers, j, len;
        containers = sidebar.querySelectorAll('.vk-group');
        if (containers) {
          for (j = 0, len = containers.length; j < len; j++) {
            container = containers[j];
            this.reload(container);
          }
        }
        return sidebar.dataset.sidebarWidth = sidebar.clientWidth;
      }

      reload(container) {
        if (typeof VK === 'object') {
          container.closest('.sidebar-widget').classList.remove('vk-widget-loaded');
          setTimeout(() => {
            var iframe;
            iframe = container.querySelector('iframe');
            if (iframe) {
              iframe.remove();
            }
            container.style = '';
            return VK.Widgets.Group(container.id, this.options, container.dataset.groupId);
          }, 300);
        } else {
          return console.error("VkGroups: VK OpenAPI not loaded");
        }
      }

    };

    VkGroups.defaults = {
      mode: 3,
      width: "auto",
      height: 226,
      color1: "ffffff",
      color2: "2b587a",
      color3: "5b7fa6",
      no_cover: 1
    };

    VkGroups.themes = {
      light: ["fefefe", "2b587a", "5b7fa6"],
      dark: ["3f3f3f", "fefefe", "777777"]
    };

    var HttpClient = class HttpClient {
      constructor() {
        this.xhr = new XMLHttpRequest();
      }

      on(event, callback, type) {
        if (type == null) {
          type = "json";
        }
        return this.xhr.addEventListener(event, (e) => {
          var response;
          if (e.target.responseText) {
            response = type === "json" ? JSON.parse(e.target.responseText) : e.target.responseText;
            return callback(response, e);
          }
        }, false);
      }

      post(url, data, headers) {
        return this.ajax("post", url, this.buildParams(data, headers));
      }

      get(url, data, headers) {
        var params, queryString;
        params = this.buildParams(data);
        queryString = params ? '?' + params.toString() : '';
        return this.ajax("get", url + queryString, {}, headers);
      }

      put(url, data, headers) {
        return this.ajax("put", url, this.buildParams(data, headers));
      }

      delete(url, data, headers) {
        return this.ajax("delete", url, this.buildParams(data, headers));
      }

      ajax(method, url, data, headers) {
        var key, value;
        this.xhr.open(method.toUpperCase(), url);
        headers = Object.assign(HttpClient.defaults.headers[method], headers);
        for (key in headers) {
          value = headers[key];
          this.xhr.setRequestHeader(key, value);
        }
        return this.xhr.send(data);
      }

      buildParams(obj) {
        var k, params;
        params = new URLSearchParams();
        for (k in obj) {
          params.append(k, obj[k]);
        }
        return params;
      }

    };

    HttpClient.defaults = {
      headers: {
        post: {
          "Content-type": "application/x-www-form-urlencoded"
        },
        get: {},
        put: {
          "Content-type": "application/x-www-form-urlencoded"
        },
        delete: {}
      }
    };

    var Widgets = class Widgets {
      constructor(selector) {
        this.attach = this.attach.bind(this);
        this.selector = selector;
        this.http = new HttpClient();
        document.addEventListener("DOMContentLoaded", this.attach, false);
      }

      attach() {
        var i, len, ref, widget, widgetTransferComplete;
        this.widgets = document.querySelectorAll(this.selector);
        ref = this.widgets;
        for (i = 0, len = ref.length; i < len; i++) {
          widget = ref[i];
          widgetTransferComplete = this.transferComplete.bind(widget, this);
          this.http.on("load", widgetTransferComplete);
          this.refresh(widget);
        }
      }

      refresh(widget) {
        var query;
        query = {
          _ajax_nonce: window.lithe.ajax.nonce,
          action: "lithe_" + widget.dataset.widgetAction,
          widget_id: widget.dataset.widgetId
        };
        return this.http.post(window.lithe.ajax.url, query);
      }

      transferComplete(widgets, response) {
        if (!response.success) {
          console.error('Widgets: ' + response.data);
          return;
        }
        this.innerHTML = response.data.html;
        this.dataset.widgetUpdated = response.data.updated;
        if (typeof this.dataset.widgetUpdateInterval !== 'undefined') {
          setTimeout(() => {
            return widgets.refresh(this);
          }, this.dataset.widgetUpdateInterval * 1000);
        }
      }

    };

    var Carousels = class Carousels {
      constructor() {
        this.init = this.init.bind(this);
        document.addEventListener("DOMContentLoaded", this.init, false);
      }

      init() {
        var carousels, id, options, ref;
        carousels = {};
        ref = Carousels.settings;
        for (id in ref) {
          options = ref[id];
          if (Carousels.settings[id]) {
            carousels[id] = jQuery("#owl-" + id).owlCarousel(options);
          }
        }
        return carousels;
      }

    };

    Carousels.settings = {
      home: {
        items: 1,
        loop: true,
        lazyLoad: true,
        lazyLoadEager: 1,
        nav: false,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: "fadeOut"
      },
      sponsors: {
        loop: true,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
          [lithe.breakpoints['chihuahua']]: {
            items: 1
          },
          [lithe.breakpoints['corgi']]: {
            items: 2
          },
          [lithe.breakpoints['aussie']]: {
            items: 3
          }
        }
      }
    };

    var Collapsible = class Collapsible {
      constructor(selector) {
        this.attach = this.attach.bind(this);
        this.changed = this.changed.bind(this);
        this.selector = selector;
        document.addEventListener("DOMContentLoaded", this.attach, false);
      }

      attach() {
        var i, len, ref, toggle;
        this.toggles = document.querySelectorAll(this.selector);
        ref = this.toggles;
        for (i = 0, len = ref.length; i < len; i++) {
          toggle = ref[i];
          toggle.addEventListener('change', this.changed, false);
        }
      }

      changed(event) {
        var cb, classname;
        cb = event.target;
        classname = cb.id + '-expanded';
        if (cb.checked) {
          return Html.tag(classname);
        } else {
          return Html.untag(classname);
        }
      }

    };

    var ScrollTop$1 = class ScrollTop {
      constructor(selector, offset) {
        this.init = this.init.bind(this);
        this.scroll = this.scroll.bind(this);
        this.selector = selector;
        this.offset = offset || 200;
        document.addEventListener("DOMContentLoaded", this.init, false);
      }

      init() {
        this.button = document.querySelector(this.selector);
        if (this.button) {
          this.button.addEventListener("click", this.goTop, false);
          document.addEventListener("scroll", this.scroll, Features.passiveListener ? {
            passive: true
          } : false);
          return this.scroll();
        }
      }

      scroll() {
        if (document.documentElement.scrollTop > this.offset) {
          return this.showButton();
        } else {
          return this.hideButton();
        }
      }

      hideButton() {
        if (this.button) {
          return this.button.classList.remove('active');
        }
      }

      showButton() {
        if (this.button) {
          return this.button.classList.add('active');
        }
      }

      goTop() {
        return document.documentElement.scrollTop = 0;
      }

    };

    var Sticky = class Sticky {
      constructor(selector) {
        this.init = this.init.bind(this);
        this.processIntersections = this.processIntersections.bind(this);
        this.selector = selector;
        document.addEventListener("DOMContentLoaded", this.init, false);
      }

      init() {
        var i, len, options, ref, sticky;
        this.stickies = document.querySelectorAll(this.selector);
        if (this.stickies) {
          options = {
            threshold: [1],
            rootMargin: (document.querySelector('#wpadminbar') ? '-33px 0px 0px 0px' : '-1px 0px 0px 0px')
          };
          this.observer = new IntersectionObserver(this.processIntersections, options);
          ref = this.stickies;
          for (i = 0, len = ref.length; i < len; i++) {
            sticky = ref[i];
            this.observer.observe(sticky);
          }
        }
        return null;
      }

      processIntersections([entry]) {
        return entry.target.classList.toggle('stuck', entry.intersectionRatio < 1);
      }

    };

    var randomNumber, range;

    range = function(size, startAt = 0) {
      return Array.from(new Array(size), (x, i) => {
        return i + startAt;
      });
    };

    randomNumber = function(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    var Views = class Views {
      constructor() {
        this.init = this.init.bind(this);
        this.storeView = this.storeView.bind(this);
        this.transferComplete = this.transferComplete.bind(this);
        this.http = new HttpClient();
        this.http.on("load", this.transferComplete);
        this.storage = window.localStorage;
        this.views = this.getViews();
        window.addEventListener("load", this.init, false);
      }

      init() {
        var i, len, options, paragraph, post, ref;
        this.posts = document.querySelectorAll('.post');
        if (this.posts) {
          options = {
            rootMargin: '0px 0px 50px 0px',
            threshold: 1
          };
          this.observer = new IntersectionObserver(this.storeView, options);
          ref = this.posts;
          for (i = 0, len = ref.length; i < len; i++) {
            post = ref[i];
            paragraph = post.querySelector('p:last-of-type');
            this.observer.observe(paragraph || post.querySelector('div:last-of-type'));
          }
          return null;
        }
      }

      storeView([entry]) {
        var post, restUri;
        if (entry.isIntersecting) {
          this.observer.unobserve(entry.target);
          post = entry.target.closest('.post');
          restUri = window.lithe.rest.root + '/posts/' + post.dataset.id + '/views';
          if (!this.seen(post.dataset.id)) {
            this.view(post.dataset.id);
            return this.http.post(restUri);
          } else {
            return this.http.get(restUri);
          }
        }
      }

      getViews() {
        var postViews;
        postViews = this.storage.getItem('postViews');
        if (postViews) {
          return JSON.parse(postViews);
        } else {
          return [];
        }
      }

      seen(postId) {
        return this.views.includes(postId);
      }

      view(postId) {
        while (this.views.length > 49) {
          this.views.shift();
        }
        this.views.push(postId);
        return this.storage.setItem('postViews', JSON.stringify(this.views));
      }

      transferComplete(response) {
        var counter, difference, previous;
        counter = document.querySelector('#post-' + response.post_id + ' .entry-views-count');
        if (!counter) {
          return;
        }
        previous = Number(counter.innerHTML);
        difference = response.views - previous;
        if (difference >= 256 || difference === 0) {
          // don't animate more than 256 views difference or with no difference if view count
          return counter.innerHTML = response.views;
        }
        return this.animateCounter(counter, range(difference, previous + 1));
      }

      animateCounter(element, views) {
        var animationInterval, cursor;
        cursor = 0;
        return animationInterval = setInterval(() => {
          element.innerHTML = views[cursor];
          if (typeof views[++cursor] === 'undefined') {
            return clearInterval(animationInterval);
          }
        }, 20);
      }

    };

    var GTag = class GTag {
      constructor(eventCategory, events1) {
        this.eventCategory = eventCategory;
        this.events = events1;
        window.dataLayer = window.dataLayer || [];
        if (this.events) {
          this.handleEvents();
        }
      }

      handleEvents() {
        var eventHandler, eventName, events, i, len, ref, ref1;
        ref = this.events;
        for (eventHandler in ref) {
          events = ref[eventHandler];
          ref1 = Object.keys(events);
          for (i = 0, len = ref1.length; i < len; i++) {
            eventName = ref1[i];
            document.addEventListener(eventName, (event) => {
              return this[eventHandler](event.detail, event);
            }, false);
          }
        }
        return null;
      }

      dataLayerPush() {
        if ('object' === typeof window.dataLayer && 'function' === typeof window.dataLayer.push) {
          return window.dataLayer.push(arguments);
        }
      }

      event(eventAction, eventLabel, eventArgs) {
        eventArgs = eventArgs || {};
        eventArgs['event_category'] = this.eventCategory;
        eventArgs['event_label'] = eventLabel;
        return this.dataLayerPush('event', eventAction, eventArgs);
      }

      withTimeout(callback, timeout) {
        var called, fn;
        called = false;
        fn = () => {
          if (!called) {
            called = true;
            return callback();
          }
        };
        setTimeout(fn, timeout || 1000);
        return fn;
      }

    };

    var boundMethodCheck$5 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var Forms = class Forms extends GTag {
      constructor() {
        super('Contact Form', {
          wpcf7: {
            'wpcf7mailsent': 'Send',
            'wpcf7mailfailed': 'Error',
            'wpcf7spam': 'Spam',
            'wpcf7submit': 'Submit'
          }
        });
        this.wpcf7 = this.wpcf7.bind(this);
      }

      wpcf7(form, event) {
        var formMeta;
        boundMethodCheck$5(this, Forms);
        formMeta = this.getFormMeta(form.contactFormId);
        if (formMeta) {
          if (formMeta.isRegistrationForm) {
            this.eventCategory = 'Registration Form';
          }
          return super.event(this.events.wpcf7[event.type], formMeta.title);
        }
      }

      getFormMeta(formId) {
        if (window._lithe_wpcf7 && window._lithe_wpcf7.hasOwnProperty(formId)) {
          return window._lithe_wpcf7[formId];
        }
      }

    };

    var boundMethodCheck$4 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var SiteTitle = class SiteTitle extends GTag {
      constructor() {
        var button, compact;
        super('Site Title');
        this.siteTitleClick = this.siteTitleClick.bind(this);
        button = document.querySelector('#site-title a');
        if (button) {
          button.addEventListener('click', this.siteTitleClick, false);
        }
        compact = document.querySelector('.compact-logo');
        if (compact) {
          compact.addEventListener('click', this.siteTitleClick, false);
        }
      }

      siteTitleClick(event) {
        var link;
        boundMethodCheck$4(this, SiteTitle);
        event.preventDefault();
        link = event.currentTarget;
        return super.event('Click', link.getAttribute('title') || 'Site Logo', {
          'event_callback': super.withTimeout(() => {
            return document.location = link.getAttribute('href');
          })
        });
      }

    };

    var boundMethodCheck$3 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var Infobar = class Infobar extends GTag {
      constructor() {
        var i, len, link, links;
        super('Infobar');
        this.detailsClick = this.detailsClick.bind(this);
        links = document.querySelectorAll('.infobar-link > a');
        if (links) {
          for (i = 0, len = links.length; i < len; i++) {
            link = links[i];
            link.addEventListener('click', this.detailsClick, false);
          }
        }
      }

      detailsClick(event) {
        var label, link;
        boundMethodCheck$3(this, Infobar);
        event.preventDefault();
        link = event.currentTarget;
        label = document.querySelector('.infobar-content');
        return super.event('Click', label.innerText || 'Infobar Link', {
          'event_callback': super.withTimeout(() => {
            return document.location = link.getAttribute('href');
          })
        });
      }

    };

    var boundMethodCheck$2 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var ScrollTop = class ScrollTop extends GTag {
      constructor() {
        var button;
        super('Scroll Top');
        this.scrollTopClick = this.scrollTopClick.bind(this);
        button = document.querySelector('#go-top');
        if (button) {
          button.addEventListener('click', this.scrollTopClick, false);
        }
      }

      scrollTopClick(event) {
        var title;
        boundMethodCheck$2(this, ScrollTop);
        title = document.querySelector('h1.entry-title');
        return super.event('Click', title ? title.innerText : 'Scroll Top Button');
      }

    };

    var boundMethodCheck$1 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var Themes = class Themes extends GTag {
      constructor() {
        super('Theme', {
          themeChange: {
            'ThemeChanged': 'Change'
          }
        });
        this.themeChange = this.themeChange.bind(this);
      }

      themeChange(theme, event) {
        boundMethodCheck$1(this, Themes);
        return super.event(this.events.themeChange[event.type], theme === 'dark' ? 'Dark' : 'Light');
      }

    };

    var Comments = class Comments extends GTag {
      constructor() {
        var form;
        super('Comments');
        form = document.querySelector('#commentform');
        if (form) {
          form.addEventListener('submit', this.commentFormSubmit, false);
        }
      }

      commentFormSubmit(event) {
        var title;
        event.preventDefault();
        title = document.querySelector('h1.entry-title');
        return super.event('Comment', title ? title.innerText : '', {
          'event_callback': super.withTimeout(() => {
            return event.target.submit();
          })
        });
      }

    };

    var boundMethodCheck = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var Sports = class Sports extends GTag {
      constructor() {
        var i, len, select, selects;
        super('Sport');
        this.sportSelected = this.sportSelected.bind(this);
        selects = document.querySelectorAll('select.sports-list');
        for (i = 0, len = selects.length; i < len; i++) {
          select = selects[i];
          select.addEventListener('change', this.sportSelected, false);
        }
      }

      sportSelected(event) {
        var option, select;
        boundMethodCheck(this, Sports);
        event.preventDefault();
        select = event.target;
        option = select.querySelector('option[value="' + select.value + '"]');
        return super.event('Selected', option ? option.textContent : select.value);
      }

    };

    var TrainerContact = class TrainerContact extends GTag {
      constructor() {
        var list;
        super('Contact');
        list = document.querySelector('#venues-list');
        if (list) {
          list.addEventListener('click', (event) => {
            var link, trainer;
            if (!event.target) {
              return;
            }
            link = event.target;
            if (link.className === 'trainer-phone-link' || link.className === 'trainer-social-link') {
              event.preventDefault();
              trainer = link.closest('.trainer-item').querySelector('h4').textContent;
              if (link.className === 'trainer-phone-link') {
                this.phoneClick(link, trainer);
              }
              if (link.className === 'trainer-social-link') {
                return this.socialClick(link, trainer);
              }
            }
          });
        }
      }

      phoneClick(link, trainer) {
        return super.event('Click', trainer + ' (' + link.textContent + ')', {
          'event_callback': super.withTimeout(() => {
            return document.location = link.getAttribute('href');
          }),
          'value': 50
        });
      }

      socialClick(link, trainer) {
        return super.event('Click', trainer + ' (' + link.getAttribute('href') + ')', {
          'event_callback': super.withTimeout(() => {
            return document.location = link.getAttribute('href');
          }),
          'value': 10
        });
      }

    };

    ({
      forms: new Forms(),
      siteTitle: new SiteTitle(),
      infobar: new Infobar(),
      scrollTop: new ScrollTop(),
      themes: new Themes(),
      comments: new Comments(),
      sport: new Sports(),
      trainerContact: new TrainerContact()
    });

    var _HashPath;

    var HashPath = {
      instance: null,
      get: function() {
        return this.instance != null ? this.instance : this.instance = new _HashPath();
      }
    };

    _HashPath = class _HashPath {
      constructor() {
        this.update = this.update.bind(this);
        this.getPath = this.getPath.bind(this);
        this.setPath = this.setPath.bind(this);
        this.resetPath = this.resetPath.bind(this);
        this.syncPath = this.syncPath.bind(this);
        this.search = this.search.bind(this);
        this.set = this.set.bind(this);
        this.toggle = this.toggle.bind(this);
        this.remove = this.remove.bind(this);
        this.path = null;
        window.addEventListener('hashchange', () => {
          this.path = this.update();
          if (this.changeCallback) {
            return this.changeCallback();
          }
        }, false);
      }

      onChange(changeCallback) {
        this.changeCallback = changeCallback;
      }

      update() {
        var hashArray, hashString, index, key, path, value;
        path = {};
        hashString = window.location.hash;
        if (hashString.length < 2 || !hashString.indexOf('/')) {
          return path;
        }
        hashArray = hashString.substring(1).split('/');
        index = 0;
        while (index < hashArray.length) {
          key = hashArray[index];
          value = hashArray[index + 1];
          path[key] = value;
          index += 2;
        }
        return path;
      }

      removeLocationHash() {
        var noHashURL;
        noHashURL = window.location.href.replace(/#.*$/, '');
        return window.history.replaceState('', document.title, noHashURL);
      }

      getPath() {
        return this.path != null ? this.path : this.path = this.update();
      }

      setPath(path1) {
        this.path = path1;
        return this.syncPath();
      }

      resetPath() {
        return this.setPath({});
      }

      syncPath() {
        var hashString, key, ref, value;
        hashString = '';
        ref = this.getPath();
        for (key in ref) {
          value = ref[key];
          hashString += '/' + key + '/' + value;
        }
        if (hashString.length === 0) {
          return this.removeLocationHash();
        } else {
          return window.location.hash = hashString.substring(1);
        }
      }

      search(query) {
        var path;
        path = this.getPath();
        if (path[query] || false) {
          return path[query];
        }
      }

      set(key, value) {
        var path;
        path = this.getPath();
        path[key] = value;
        return this.setPath(path);
      }

      toggle(key, value, toggle) {
        if (typeof toggle === 'undefined') {
          if (this.search(key)) {
            this.remove(key);
          } else {
            this.set(key, value);
          }
        }
        if (toggle === false) {
          return this.remove(key);
        } else {
          return this.set(key, value);
        }
      }

      remove(key) {
        var path;
        path = this.getPath();
        delete path[key];
        return this.setPath(path);
      }

    };

    var Venues = class Venues {
      constructor() {
        this.init = this.init.bind(this);
        this.redrawVenueLists = this.redrawVenueLists.bind(this);
        this.initLists = this.initLists.bind(this);
        this.getLocation = this.getLocation.bind(this);
        this.calculateDistance = this.calculateDistance.bind(this);
        this.getVenues = this.getVenues.bind(this);
        this.venuesComplete = this.venuesComplete.bind(this);
        this.getTrainers = this.getTrainers.bind(this);
        this.user = {};
        this.venuesItems = [];
        HashPath.get().onChange(this.init);
        ymaps.ready(() => {
          this.objectManager = new ymaps.ObjectManager();
          this.objectManager.objects.options.set({
            iconLayout: 'default#image',
            iconImageHref: lithe.home + '/wp-content/themes/lithe/assets/js/placemark.png',
            iconImageSize: [40, 45],
            iconImageOffset: [-20, -44]
          });
          this.venuesMap = new ymaps.Map('venues-map', {
            center: [59.880, 30.3],
            zoom: 10,
            controls: ['typeSelector', 'zoomControl']
          }, {
            restrictMapArea: [[59, 29], [61, 32]]
          });
          this.venuesMap.behaviors.disable('scrollZoom');
          this.venuesMap.geoObjects.add(this.objectManager);
          this.venuesMap.geoObjects.events.add('click', this.redrawVenueLists);
          this.venuesMap.balloon.events.add('close', this.redrawVenueLists);
          return this.init();
        });
        this.venueList = document.querySelector('#venues-list');
        this.venues = new HttpClient();
        this.venues.on("load", this.venuesComplete);
      }

      init() {
        var slug, sportId;
        slug = HashPath.get().search('sport') || 'all';
        if (sportId = this.initLists(slug)) {
          return this.getVenues(sportId);
        }
      }

      redrawVenueLists(event) {
        var i, item, len, ref, venueId;
        venueId = event.get('objectId');
        ref = document.querySelectorAll('.venue-item');
        for (i = 0, len = ref.length; i < len; i++) {
          item = ref[i];
          if (venueId) {
            item.classList.toggle('translucent', Number(item.dataset.venueId) !== venueId);
          } else {
            item.classList.remove('translucent');
          }
        }
      }

      initLists(slug) {
        var i, len, list, lists, option, ret;
        ret = false;
        lists = document.querySelectorAll('.sports-list');
        for (i = 0, len = lists.length; i < len; i++) {
          list = lists[i];
          list.addEventListener('change', () => {
            var option, sportId;
            sportId = event.target.value;
            option = list.querySelector('option[value="' + sportId + '"]');
            slug = option.dataset.slug;
            HashPath.get().toggle('sport', slug, slug !== 'all');
            return this.getVenues(sportId);
          });
          if (slug) {
            option = list.querySelector('option[data-slug="' + slug + '"]');
            if (option) {
              ret = list.value = option.value;
            }
          }
        }
        return ret;
      }

      getLocation() {
        if (!this.user.coordinates) {
          ymaps.geolocation.get().then((result) => {
            result.geoObjects.options.set('preset', 'islands#bluePersonCircleIcon');
            result.geoObjects.get(0).properties.set({
              hintContent: yandexmaps_l10n.youarehere
            });
            this.venuesMap.geoObjects.add(result.geoObjects);
            this.user.coordinates = result.geoObjects.get(0).geometry.getCoordinates();
            this.calculateDistance();
            return this.sortVenues();
          });
          return;
        }
        this.calculateDistance();
        return this.sortVenues();
      }

      calculateDistance() {
        var distanceContainer, i, len, ref, venue, venueCoordinates, venueDistance;
        ref = this.venuesItems;
        for (i = 0, len = ref.length; i < len; i++) {
          venue = ref[i];
          if (venue.dataset.venueCoordinates) {
            venueCoordinates = venue.dataset.venueCoordinates.split(',');
            venueDistance = ymaps.coordSystem.geo.getDistance(this.user.coordinates, venueCoordinates);
            venue.dataset.venueDistance = venueDistance;
            distanceContainer = venue.querySelector('.venue-distance');
            if (distanceContainer) {
              distanceContainer.innerHTML = '~ ' + ymaps.formatter.distance(venueDistance) + ' ' + lithe_l10n.from_your_position;
            }
          }
        }
      }

      sortVenues() {
        var i, len, ref, venue;
        this.venuesItems.sort((a, b) => {
          return +a.dataset.venueDistance - +b.dataset.venueDistance;
        });
        this.venueList.innerHTML = '';
        ref = this.venuesItems;
        for (i = 0, len = ref.length; i < len; i++) {
          venue = ref[i];
          this.venueList.appendChild(venue);
        }
      }

      getVenues(sportId) {
        if (typeof sportId === 'undefined') {
          sportId = 0;
        }
        this.venueList.dataset.sportId = sportId;
        this.venueList.classList.add('loading');
        return this.venues.get(window.lithe.rest.root + '/venues', {
          'sport_id': sportId
        });
      }

      venuesComplete(response) {
        var html, i, item, len, ref, venue;
        if (response.meta.placemarks) {
          this.objectManager.removeAll();
          this.objectManager.add(response.meta.placemarks);
        }
        this.venueList.innerHTML = '';
        if (response.meta.count === 1) {
          this.venueList.className = 'single-column';
        }
        if (response.meta.count === 2) {
          this.venueList.className = 'two-columns';
        }
        if (response.meta.count === 3) {
          this.venueList.className = 'three-columns';
        }
        if (response.meta.count > 3) {
          this.venueList.className = 'four-columns';
        }
        this.venuesItems = [];
        ref = response.data;
        for (i = 0, len = ref.length; i < len; i++) {
          venue = ref[i];
          item = document.createElement('div');
          item.classList.add('venue-item', 'loading');
          item.dataset.venueId = venue.id;
          item.dataset.venueCoordinates = venue.coords;
          html = '<div class="venue-item-wrap"><header><h3 class="venue-title">' + venue.name + '</h3>';
          html += '<span class="venue-address">' + venue.address + '</span>';
          if (venue.coords) {
            html += '<span class="venue-distance"></span>';
          }
          html += '</header>';
          if (typeof venue.description !== 'undefined' && venue.description !== '') {
            html += '<div class="venue-description">' + venue.description + '</div>';
          }
          html += this.getTrainerPlaceholder('primary');
          html += this.getTrainerPlaceholder('secondary');
          item.innerHTML = html + '</div>';
          this.trainers = new HttpClient();
          this.trainers.on("load", this.trainersComplete.bind(item.querySelector('.venue-item-wrap'), item, this));
          this.getTrainers(venue.id);
          this.venueList.appendChild(item);
          this.venuesItems.push(item);
        }
        this.venueList.classList.remove('loading');
        this.getLocation();
      }

      getTrainers(venueId) {
        var sportId;
        sportId = this.venueList.dataset.sportId;
        return this.trainers.get(window.lithe.rest.root + '/trainers', {
          'venue_id': venueId,
          'sport_id': sportId
        });
      }

      trainersComplete(item, self, response) {
        var html, i, j, len, len1, ref, ref1, sport, sports, trainer;
        item.classList.remove('loading');
        ref = response.data;
        for (i = 0, len = ref.length; i < len; i++) {
          trainer = ref[i];
          sports = [];
          ref1 = trainer.sports;
          for (j = 0, len1 = ref1.length; j < len1; j++) {
            sport = ref1[j];
            sports.push('<li>' + sport.name + '</li>');
          }
          item = document.createElement('div');
          item.classList.add('trainer-item');
          html = '<header><h4>' + trainer.last_name + ' ' + trainer.first_name + '</h4>';
          html += self.getTrainerPhoto(trainer);
          html += '<ul class="trainer-sports sport-tags">' + sports.join('') + '</ul></header>';
          html += '<ul class="trainer-contact-info">';
          html += '<li class="trainer-phone"><a class="trainer-phone-link" href="tel:' + trainer.phone + '"><i class="fas fa-phone fa-fw ignores-pointer-events"></i>' + trainer.phone + '</a></li>';
          if (trainer.social) {
            html += '<li class="trainer-social"><a class="trainer-social-link" href="' + trainer.social + '"><i class="fab fa-vk fa-fw ignores-pointer-events"></i></a></li>';
          }
          html += '</ul>';
          if (typeof trainer.timetable !== 'undefined' && trainer.timetable !== '') {
            html += '<div class="trainer-timetable">' + trainer.timetable + '</div>';
          }
          item.innerHTML = html;
          this.appendChild(item);
        }
      }

      getTrainerPhoto(trainer) {
        var container;
        container = document.createElement('span');
        container.classList.add('trainer-photo');
        if (!trainer.photo) {
          container.classList.add('placeholder-' + randomNumber(1, 6));
          return container.outerHTML;
        }
        container.innerHTML = '<img src="' + trainer.photo.src + '" alt="">';
        if (trainer.photo.width < trainer.photo.height) {
          container.classList.add('portrait');
        }
        return container.outerHTML;
      }

      getTrainerPlaceholder(classNames) {
        var html;
        html = '';
        html += '<div class="trainer-item placeholder ' + classNames + '"><header><span class="trainer-photo"></span></header>';
        html += '<ul class="trainer-contact-info"><li class="trainer-phone"></li><li class="trainer-social"></li></ul></div>';
        return html;
      }

    };

    var Switcheroo = class Switcheroo {
      constructor() {
        this.init = this.init.bind(this);
        this.processSwicheroo = this.processSwicheroo.bind(this);
        this.performSwitcheroo = this.performSwitcheroo.bind(this);
        this.getFormData = this.getFormData.bind(this);
        this.map = {};
        this.inputs = ['_wpcf7', '_wpcf7_unit_tag', '_wpcf7_container_post'];
        document.addEventListener("DOMContentLoaded", this.init, false);
      }

      init() {
        var form, i, len, ref;
        ref = document.querySelectorAll('.wpcf7-form');
        for (i = 0, len = ref.length; i < len; i++) {
          form = ref[i];
          this.processSwicheroo(form.querySelector("[data-switcheroo]"));
        }
        return null;
      }

      processSwicheroo(switcheroo) {
        var i, len, part, ref, switcherooInput, token;
        switcherooInput = switcheroo.querySelector("select");
        if (!switcheroo || !switcherooInput) {
          return false;
        }
        switcherooInput.addEventListener("change", this.performSwitcheroo, false);
        ref = switcheroo.dataset.switcheroo.split(",");
        for (i = 0, len = ref.length; i < len; i++) {
          part = ref[i];
          token = part.split(":");
          this.map[token[0]] = token[1];
        }
        return null;
      }

      performSwitcheroo(event) {
        var data, form, formId, key, parent, switcheroo, unitTag;
        switcheroo = event.target;
        key = switcheroo.value;
        if (typeof this.map[key] === "undefined") {
          return false;
        }
        form = switcheroo.closest(".wpcf7-form");
        if (!form) {
          return false;
        }
        data = this.getFormData(form);
        formId = this.map[key];
        unitTag = "wpcf7-f" + formId + "-p" + data._wpcf7_container_post.value + "-o1";
        parent = form.closest(".wpcf7");
        parent.id = unitTag;
        data._wpcf7.value = formId;
        data._wpcf7_unit_tag.value = unitTag;
        form.action = window.location.href + "#" + unitTag;
        form.wpcf7.id = formId;
        form.wpcf7.unitTag = unitTag;
        return null;
      }

      getFormData(form) {
        var data, i, input, len, ref;
        data = {};
        ref = this.inputs;
        for (i = 0, len = ref.length; i < len; i++) {
          input = ref[i];
          data[input] = form.querySelector('input[name="' + input + '"]');
        }
        return data;
      }

    };

    var Lithe;

    Lithe = {
      jsIsAvailable: function() {
        return Html.untag('nojs');
      },
      init: function() {
        Lithe.jsIsAvailable();
        Lithe.vkGroups = new VkGroups('.vk-group');
        Lithe.widgets = new Widgets('[data-widget-action]');
        Lithe.carousels = new Carousels();
        Lithe.collapsible = new Collapsible('.collapsible-toggle');
        Lithe.sticky = new Sticky('.sticky');
        Lithe.scrollTop = new ScrollTop$1('#go-top', 768);
        Lithe.views = new Views();
        if (document.querySelector('body.page-template-template-venues')) {
          Lithe.venues = new Venues();
        }
        if (document.querySelector('[data-switcheroo]')) {
          Lithe.switcheroo = new Switcheroo();
        }
      }
    };

    window.lithe = Object.assign(Lithe, window.lithe);

    window.lithe.init();

})();
