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

var passiveListener;

var Features = passiveListener = () => {
  var passiveSupported;
  passiveSupported = false;
  try {
    window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
      get: () => {
        return passiveSupported = true;
      }
    }));
  } catch (error) {
  }
  return passiveSupported;
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
    var color, i, j, len, ref, theme;
    theme = event.detail;
    if (VkGroups.themes[theme]) {
      ref = VkGroups.themes[theme];
      for (i = j = 0, len = ref.length; j < len; i = ++j) {
        color = ref[i];
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

var ScrollTop = class ScrollTop {
  constructor(selector, offset) {
    this.init = this.init.bind(this);
    this.scroll = this.scroll.bind(this);
    this.goTop = this.goTop.bind(this);
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

var Views = class Views {
  constructor() {
    this.init = this.init.bind(this);
    this.storeView = this.storeView.bind(this);
    this.http = new HttpClient();
    this.http.on("load", this.transferComplete);
    this.storage = window.localStorage;
    this.views = this.getViews();
    document.addEventListener("DOMContentLoaded", this.init, false);
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
    var counter;
    counter = document.querySelector('#post-' + response.post_id + ' .entry-views-count');
    if (counter) {
      return counter.innerHTML = response.views_human;
    }
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

var boundMethodCheck = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

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
    boundMethodCheck(this, Forms);
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

var boundMethodCheck$1 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

var SiteTitle = class SiteTitle extends GTag {
  constructor() {
    super('Site Title');
    this.siteTitleClick = this.siteTitleClick.bind(this);
    this.button = document.querySelector('#site-title a');
    if (this.button) {
      this.button.addEventListener('click', this.siteTitleClick, false);
    }
  }

  siteTitleClick(event) {
    boundMethodCheck$1(this, SiteTitle);
    event.preventDefault();
    return super.event('Click', this.button.getAttribute('title') || 'Site Logo', {
      'event_callback': super.withTimeout(() => {
        return document.location = this.button.getAttribute('href');
      })
    });
  }

};

var boundMethodCheck$2 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

var Infobar = class Infobar extends GTag {
  constructor() {
    var i, len, link, links;
    super('Infobar');
    this.detailsClick = this.detailsClick.bind(this);
    links = document.querySelectorAll('.infobar-link');
    if (links) {
      for (i = 0, len = links.length; i < len; i++) {
        link = links[i];
        link.addEventListener('click', this.detailsClick, false);
      }
    }
  }

  detailsClick(event) {
    var label, link;
    boundMethodCheck$2(this, Infobar);
    event.preventDefault();
    link = event.target;
    label = link.previousElementSibling;
    return super.event('Click', label.innerText || 'Infobar Link', {
      'event_callback': super.withTimeout(() => {
        return document.location = link.getAttribute('href');
      })
    });
  }

};

var boundMethodCheck$3 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

var ScrollTop$1 = class ScrollTop extends GTag {
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
    boundMethodCheck$3(this, ScrollTop);
    title = document.querySelector('h1.entry-title');
    return super.event('Click', title ? title.innerText : 'Scroll Top Button');
  }

};

var boundMethodCheck$4 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

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
    boundMethodCheck$4(this, Themes);
    return super.event(this.events.themeChange[event.type], theme === 'dark' ? 'Dark' : 'Light');
  }

  getFormMeta(formId) {
    if (window._lithe_wpcf7 && window._lithe_wpcf7.hasOwnProperty(formId)) {
      return window._lithe_wpcf7[formId];
    }
  }

};

var boundMethodCheck$5 = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

var Comments = class Comments extends GTag {
  constructor() {
    super('Comments');
    this.commentFormSubmit = this.commentFormSubmit.bind(this);
    this.form = document.querySelector('#commentform');
    if (this.form) {
      this.form.addEventListener('submit', this.commentFormSubmit, false);
    }
  }

  commentFormSubmit(event) {
    var title;
    boundMethodCheck$5(this, Comments);
    event.preventDefault();
    title = document.querySelector('h1.entry-title');
    return super.event('Comment', title ? title.innerText : '', {
      'event_callback': super.withTimeout(() => {
        return this.form.submit();
      })
    });
  }

};

var GTag$1 = {
  forms: new Forms(),
  siteTitle: new SiteTitle(),
  infobar: new Infobar(),
  scrollTop: new ScrollTop$1(),
  themes: new Themes(),
  comments: new Comments()
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
    Lithe.scrollTop = new ScrollTop('#go-top', 768);
    Lithe.views = new Views();
  }
};

window.lithe = Object.assign(Lithe, window.lithe);

window.lithe.init();
