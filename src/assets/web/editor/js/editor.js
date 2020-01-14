var currentLocation = document.location.toString();
var activeHash = currentLocation.split('#')[1];
var localStorageKey = 'dmstr.yii2.prototype.lastActiveHash';

$(function(){
  if (typeof activeHash === "undefined") {
    var lastActiveHash = localStorage.getItem(localStorageKey);
    if (lastActiveHash) {
      activeHash = lastActiveHash;
    }
  } else {
    activeHash = '#' + activeHash;
  }

  var tabEl = $('.editor-top-navigation a[data-target="' + activeHash + '"]');

  if (tabEl.length < 1) {
    tabEl = $('.editor-top-navigation > li:first-of-type > a')
  }

  if (window.location.pathname.indexOf('new') > -1) {
    tabEl = $('.editor-top-navigation a[data-target="#tab-9999999"]');
  }

  tabEl.tab('show');

  $('.editor-top-navigation a').on('shown.bs.tab', function (e) {
    e.preventDefault();
    $(this).tab('show');
    var newActiveHash = $(e.target).data('target');
    window.location.hash = newActiveHash;
    localStorage.setItem(localStorageKey, newActiveHash);
  });

  // remember user to save stuff
  window.onbeforeunload = function() {
    return true;
  };
});
