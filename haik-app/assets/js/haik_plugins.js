/**
 *   Haik Plugins
 *   -------------------------------------------
 *   js/haik_plugins.js
 *   
 *   Copyright (c) 2014 hokuken
 *   http://hokuken.com/
 *   
 *   created  : 12/10/25
 *   modified : 14/01/10
 */

if (typeof haik == "undefined") {
	haik = {};
}

// !plugin を全て初期化
haik.plugins = {
	
	// !見出し
	header: {
		label: "# 見出し",
		format: "# {header}\n",
		options: {defval: "テキスト", maxRepeat: 3},
		onStart: function(){
			var exnote = $(this.textarea).data("exnote"),
				value = this.options.defval,
				text = exnote.getSelectedText(),
				multiLine = false, newLines = "",
				values, self = this;
			
			exnote.adjustSelection();
			text = exnote.getSelectedText();

			if (/\n/.test(text)) {
				multiLine = true;
				values = text.replace(/(\n+)$/g, "").split("\n");
				newLines = RegExp.$1;
			}
			else {
				values = [text];
			}
			
			values = $.map(values, function(value, i){
				value = value.replace(/^\s+|\s+$/g, "");
				if (value.length === 0) {
					if (multiLine) {
						return "";
					}
					value = "# " + self.options.defval;
				}
				else {
					value = "#" + value.replace(/^(\*+|) */, "$1 ");
					value = value.replace(/^\*{4,} (.*)$/, "### $1");
				}
				return value;
			});
			
			this.value = values.join("\n");

			if (multiLine) {
				this.value += newLines;
				this.caret = {
					offset: - (this.value.length),
					length: this.value.length - newLines.length
				};
			}
			else {
				text = this.value.match(/^\*{1,3} ?(.*)$/) ? RegExp.$1 : "";
				this.caret = {
					offset: - (text.length),
					length: text.length
				};
			}
		}
	},
	
	// !箇条書き
	ul: {
		label: "- リスト",
		format: "- {text}",
		options: {
			defval: "リスト",
			lineNum: 3
		},
		onComplete :function(){
			var self = this;
      var exnote = $(this.textarea).data("exnote");
			
			exnote.adjustSelection();
			var text = exnote.getSelectedText()
			  , lines = [], value = "", caret = null;
			
			if (text.length > 0) {
				var lines = text.split("\n");
				
				_.forEach(lines, function(v, i){
					lines[i] = v.replace(/^/, "- ");
				});
			}
			else {
				for (var i = 0; i < self.options.lineNum; i++) {
					lines.push(self.format.replace("{text}", self.options.defval));
				}
			}
			
			value = lines.join("\n");
			caret = {offset: -value.length, length: value.length};

			self.insert(value, caret);

		}
	},

	// !改行
	br: {
		label: "改行",
		value: "/(br)"
	},

	// !文字装飾（強調）
	strong: {
		label: "**強調**",
		format: "**{text}**",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote"), text = "";
			if (exnote.selectLength > 0) {
				text = exnote.getSelectedText();
				if (/\n/.test(text)) {
					this.value = "";
					var lines = text.split("\n"), values = [];
					for (var i = 0; i < lines.length; i++) {
						var line = lines[i];
						values.push(this.format.replace("{text}", line));
					}
					this.value = values.join("\n");
					return;
				}
			}
			this.value = this.format.replace("{text}", text);
		}
	},

	// !セクション
	section: {
  	label: "セクション",
		format: "${br}:::section${br}${br}# タイトル${br}${br}セクションの文章などなど${br}${br}<!-- 下の区切りを入れると段組みになります -->${br}====${br}${br}2段目の内容${br}${br}----${br}#セクションのオプションを指定します${br}${br}#文字の位置${br}align: center${br}${br}#文字の縦方向の位置${br}valign: middle${br}:::",
		onStart: function(){
		  var data = {br: "\n"};

			var value = this.format.replace(/\$\{br\}/g, "\n");
		  this.value = value;
		}
	},

	// !水平線
	hr: {
		label: "区切り",
		value: "----\n",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			
			exnote.moveToNextLine();
		}
	},
	
	// ! インデント
	indent: {
		label: "タブ",
		value: "    ",
		onStart: function(){
		}
	},
	
	// !画像
	show: {
		label: "画像",
		options: {
			defval: {
				text: "ここに文章を入れてください。"
			},
			formats: {
				normal: '&show({name},,{title});',
				popup: '&show({name},popup,lighter,{title});',
				pola: '&show({name},pola,{title});'
			},
			filer: {
				options: {
					search_word: ":image",
					select_mode: ""
				},
				footer: '\
					<div class="btn-group" data-toggle="buttons">\
						<label class="btn btn-default active"><input type="radio" name="type" id="" value="normal" checked> 通常</label>\
						<label class="btn btn-default"><input type="radio" name="type" id="" value="popup"> ポップアップ</label>\
						<label class="btn btn-default"><input type="radio" name="type" id="" value="pola"> 枠付き</label>\
					</div>\
					<button type="button" data-submit class="btn btn-primary">貼り付け</button>\
					<button type="button" data-dismiss="modal" class="btn btn-default">キャンセル</button>\
				'
			}
		},
		init: false,
		onStart: function(){
			var self = this;
			var $filer = $("#haik_filer_selector")
			  , files = [];
			
			$filer.find("iframe").data(self.options.filer.options);
			$filer.data("footer", self.options.filer.footer).modal();
			
			$filer.on("hidden.bs.modal", function(){
				$(document).off("selectFiles.pluginShow");
			})
			.on("click.pluginShow", "[data-submit]", function(){
				self.insertFiles(files);
				$filer.modal('hide');
				$filer.off("click.pluginShow");
			});
			
			$(document).on("selectFiles.pluginShow", function(e, selectedFiles){
				
				files = selectedFiles;
				
			});
			
			return false;
		},
		insertFiles: function(files){
			
			var self = this
			  , $filer = $("#haik_filer_selector")
			  , exnote = $(this.textarea).data("exnote")
			  , type = $filer.find("div.modal-footer input:radio:checked").val()
			  , text = self.options.defval.text;
			  
			if (files.length > 0) {
				var value = [];
				for (var i = 0; i < files.length; i++) {
					var file = files[i];
					value.push(self.options.formats[type].replace("{name}", file.filename).replace("{title}", file.title).replace("{text}", text));
					text = self.options.defval.text;//選択テキストを使うのは1度だけ
				}
				value = value.join("\n");
				
				var caret = {offset: -value.length, length: value.length};
				self.insert(value, caret);
			}
			
		}
	},

	// !リンク
	link: {
		label: "リンク",
		format: '[${alias}](URL)',
		options: {defval: "表示"},
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			var text = exnote.getSelectedText();

			if (text.length === 0) {
			  text = this.options.defval;
			}

			this.value = '[' + text + '](URL)';
		}
	},
	
	// !ボタン
	button: {
		label: "ボタン",
		format: '/[${alias}](button)',
		options: {defval: "表示"},
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			var text = exnote.getSelectedText();

			if (text.length === 0) {
			  text = this.options.defval;
			}
			
			this.value = '/[' + text + '](button)';
		}
	},
	
	// !その他
	allPlugin: {
		label: ">>",
		addable: false,
		onStart: function(){
			haik.PluginHelper.openList();
			return false;
		}
	},
  
  // !プラグイン履歴
	recentPlugins: {
		label: "履歴",
		labelPrefix: '<span><i class="haik-icon haik-icon-clock"></i> </span>',
		labelSuffix: '<span> <span class="caret"></span></span>',
		addable: false,
		init: false,
		onStart: function(){
			var self = this,
				$element = $(self.element);
			if ( ! self.init) {
				$element
				.attr("data-toggle", "dropdown")
				self.init = true;
			}
			$element.nextAll('.dropdown-menu').remove();
			
			if (haik.PluginHelper.recent !== false) {
				var $ul = $('<ul/>', {"class": "dropdown-menu"});
				
				var list = [];
				
				_.forEach(haik.PluginHelper.recent, function(name, i){
					if (typeof haik.plugins[name] === "undefined") {
						return;
					}
					
					var num = (i + 1);
					num = "0" + num.toString();
					num = num.substr(num.length - 2);
					list.push('<li><a href="#" data-name="'+name+'" data-textarea="#msg">'+ num + ". " + _.escape(haik.plugins[name].label) +'</a></li>');
				});
				$ul.append(list.join(""))
				.on("click", "a[data-name]", function(e){
					e.preventDefault();
					
					if (typeof $(this).data("HaikPluginHelper") === "undefined") {
						haik.PluginHelper.init(this);
						$(this).data("HaikPluginHelper").exec();
					}
				});
				
				$(self.element).after($ul);

			}

			return false;
		}
	},

	// !次の行へ移動
	moveToNextLine: {
		label: "次の行へ移動",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			exnote.moveToNextLine();
			var range = exnote.getRange();
			exnote.attachFocus(range.position, range.length);
			return false;
		}
	},

	// !行頭へ移動
	moveToLinehead: {
		label: "行頭へ移動",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			exnote.moveToLineHead();
			var range = exnote.getRange();
			exnote.attachFocus(range.position, range.length);
			return false;
		}
	},

	// !選択範囲を調整
	adjustSelection: {
		label: "選択範囲を調整",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			exnote.adjustSelection();
			var range = exnote.getRange();
			exnote.attachFocus(range.position, range.length);
			return false;
		}
	},
	
	undo: {
		label: "戻す",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			exnote.undo();
			return false;
		}
	},

	redo: {
		label: "やり直す",
		onStart: function(){
			var exnote = $(this.textarea).data("exnote");
			exnote.redo();
			return false;
		}
	}
	
};
