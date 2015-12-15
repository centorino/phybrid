var App = (function() {
  this.initialize.apply(this, arguments);
});

App.prototype = {
  // コンストラクタ
  initialize: function() {
    this.addEventListeners();
  },
  // イベントリスナーの追加
  addEventListeners: function() {
    var self = this;
    
    $('a[data-http-method="delete"]').click(function(event) { return self.onClickDataDeleteMethod(event, this); });
    $('a[data-http-method="post"]').click(function(event) { return self.onClickDataPostMethod(event, this); });
  },
  // 初期フォーカスの設定
  setInitFocus: function() {
    var elems = $('input[type="text"],input[type="password"]');
    if (elems.length > 0) $(elems[0]).focus();
  },
  // イベントのキャンセル
  cancelEvents: function(event) {
    event.preventDefault();
    event.stopPropagation();
  },
  // method="delete"なリンクでの削除処理
  onClickDataDeleteMethod: function(event, obj_) {
    this.cancelEvents(event);
    
    var show_conf = $(obj_).attr('data-confirm');
    if (show_conf === undefined || show_conf != 'none')
    {
      if (!confirm('削除してよろしいですか？')) {
        return;
      }
    }
    
    var _form = document.createElement('form');
    _form.setAttribute('action', $(obj_).attr('href'));
    _form.setAttribute('method', 'POST');
    var _method = document.createElement('input');
    _method.setAttribute('type', 'hidden');
    _method.setAttribute('name', '_METHOD');
    _method.value = 'DELETE';
    _form.appendChild(_method);
    document.body.appendChild(_form);
    $(_form).submit();
  },
  // method="post"なリンクでの削除処理
  onClickDataPostMethod: function(event, obj_) {
    this.cancelEvents(event);
    
    var _form = document.createElement('form');
    _form.setAttribute('action', $(obj_).attr('href'));
    _form.setAttribute('method', 'POST');
    document.body.appendChild(_form);
    $(_form).submit();
  }
};



function init() {
  var app = new App();
  app.setInitFocus();
}

$(document).ready(function() {
  init();
});