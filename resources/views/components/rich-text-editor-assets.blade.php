@once
<link href="{{ asset('admin/css/quill.snow.css') }}" rel="stylesheet" type="text/css">
<style>
.ql-snow.ql-toolbar button.ql-table,
.ql-snow .ql-toolbar button.ql-table {
	position: relative;
}
.ql-snow.ql-toolbar button.ql-table svg:not(.ql-table-icon),
.ql-snow .ql-toolbar button.ql-table svg:not(.ql-table-icon) {
	display: none;
}
.ql-editor table {
	width: 100%;
	border-collapse: collapse;
	margin: 12px 0;
	table-layout: fixed;
}
.ql-editor table td,
.ql-editor table th {
	border: 1px solid #cfd6e4;
	padding: 8px 10px;
	min-width: 48px;
	vertical-align: top;
	background: #fff;
}
.ql-editor table th {
	background: #f4f7fb;
	font-weight: 600;
}
</style>
<script src="{{ asset('admin/js/quill.min.js') }}"></script>
<script>
(function () {
	function parseOptions(wrap) {
		var raw = wrap.getAttribute('data-quill-options') || '{}';
		try {
			return JSON.parse(raw);
		} catch (e) {
			return {};
		}
	}

	function registerTableBlot() {
		if (typeof Quill === 'undefined' || Quill.__blogTableRegistered) {
			return;
		}

		var BlockEmbed = Quill.import('blots/block/embed');

		function tableInnerHtml(rows, cols) {
			var html = '<thead><tr>';
			var c;
			var r;
			for (c = 0; c < cols; c++) {
				html += '<th><br></th>';
			}
			html += '</tr></thead><tbody>';
			for (r = 1; r < rows; r++) {
				html += '<tr>';
				for (c = 0; c < cols; c++) {
					html += '<td><br></td>';
				}
				html += '</tr>';
			}
			html += '</tbody>';
			return html;
		}

		class TableBlot extends BlockEmbed {
			static create(value) {
				var node = super.create();
				var html = '';
				if (typeof value === 'string') {
					html = value;
				} else if (value && value.html) {
					html = value.html;
				} else {
					var rows = Math.min(10, Math.max(2, parseInt(value && value.rows, 10) || 3));
					var cols = Math.min(10, Math.max(2, parseInt(value && value.cols, 10) || 3));
					html = tableInnerHtml(rows, cols);
				}
				node.innerHTML = html;
				node.setAttribute('contenteditable', 'false');
				Array.prototype.forEach.call(node.querySelectorAll('th, td'), function (cell) {
					cell.setAttribute('contenteditable', 'true');
				});
				return node;
			}

			static value(node) {
				return { html: node.innerHTML };
			}
		}

		TableBlot.blotName = 'blogTable';
		TableBlot.tagName = 'TABLE';

		Quill.register(TableBlot, true);
		Quill.__blogTableRegistered = true;
	}

	function enhanceTables(root) {
		if (!root) {
			return;
		}
		Array.prototype.forEach.call(root.querySelectorAll('table'), function (table) {
			table.setAttribute('contenteditable', 'false');
			Array.prototype.forEach.call(table.querySelectorAll('th, td'), function (cell) {
				cell.setAttribute('contenteditable', 'true');
			});
		});
	}

	function serializeHtml(html) {
		var tmp = document.createElement('div');
		tmp.innerHTML = html || '';
		Array.prototype.forEach.call(tmp.querySelectorAll('[contenteditable]'), function (el) {
			el.removeAttribute('contenteditable');
		});
		return tmp.innerHTML;
	}

	function insertTable(quill) {
		var rows = parseInt(window.prompt('Number of rows', '3'), 10);
		var cols = parseInt(window.prompt('Number of columns', '3'), 10);
		if (!rows || !cols) {
			return;
		}
		var range = quill.getSelection(true) || { index: quill.getLength() };
		quill.insertEmbed(range.index, 'blogTable', { rows: rows, cols: cols }, 'user');
		quill.setSelection(range.index + 1, 0, 'silent');
		enhanceTables(quill.root);
	}

	function ensureTableIcon(quill) {
		var toolbar = quill.getModule('toolbar');
		var btn = toolbar && toolbar.container ? toolbar.container.querySelector('button.ql-table') : null;
		if (!btn || btn.querySelector('svg.ql-table-icon')) {
			return;
		}
		btn.innerHTML = '<svg class="ql-table-icon" viewBox="0 0 18 18">' +
			'<rect class="ql-stroke" height="12" width="16" x="1" y="3"></rect>' +
			'<line class="ql-stroke" x1="1" x2="17" y1="7.5" y2="7.5"></line>' +
			'<line class="ql-stroke" x1="1" x2="17" y1="12" y2="12"></line>' +
			'<line class="ql-stroke" x1="7" x2="7" y1="3" y2="15"></line>' +
			'<line class="ql-stroke" x1="12" x2="12" y1="3" y2="15"></line>' +
			'</svg>';
	}

	function teardownCmsQuillRoot(wrap) {
		if (!wrap) {
			return;
		}
		var tabPaneId = wrap.getAttribute('data-quill-tab-pane');
		if (tabPaneId && wrap._cmsQuillTabHandler) {
			var tabBtn = document.querySelector('[data-bs-target="#' + tabPaneId + '"]');
			if (tabBtn) {
				tabBtn.removeEventListener('shown.bs.tab', wrap._cmsQuillTabHandler);
			}
			wrap._cmsQuillTabHandler = null;
		}
		var edId = wrap.getAttribute('data-quill-editor-id');
		var host = edId ? document.getElementById(edId) : null;
		if (host && host.querySelector('.ql-editor')) {
			var fresh = document.createElement('div');
			fresh.id = host.id;
			fresh.style.minHeight = host.style.minHeight || '200px';
			host.parentNode.replaceChild(fresh, host);
		}
		var ta = wrap.querySelector('textarea');
		if (ta) {
			ta.removeAttribute('data-quill-form-bound');
		}
		wrap.removeAttribute('data-quill-initialized');
		wrap.removeAttribute('data-quill-bind-done');
	}

	function mountOne(wrap) {
		if (wrap.closest('#section-prototype, #item-prototype, #link-prototype')) {
			return;
		}
		if (wrap.getAttribute('data-quill-bind-done') === '1') {
			return;
		}
		var editorId = wrap.getAttribute('data-quill-editor-id');
		var textareaId = wrap.getAttribute('data-quill-textarea-id');
		var tabPaneId = wrap.getAttribute('data-quill-tab-pane');
		if (!editorId || !textareaId) {
			return;
		}
		wrap.setAttribute('data-quill-bind-done', '1');

		function initEditor() {
			var editorElement = document.getElementById(editorId);
			var textarea = document.getElementById(textareaId);
			if (!editorElement || !textarea) {
				return;
			}
			if (editorElement.querySelector('.ql-editor')) {
				return;
			}
			if (typeof Quill === 'undefined') {
				setTimeout(initEditor, 100);
				return;
			}

			registerTableBlot();

			var opts = parseOptions(wrap);
			var quillConfig = {
				theme: 'snow',
				modules: {
					toolbar: {
						container: [
							[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
							[{ 'font': [] }],
							[{ 'size': [] }],
							['bold', 'italic', 'underline', 'strike'],
							[{ 'color': [] }, { 'background': [] }],
							[{ 'script': 'sub'}, { 'script': 'super' }],
							[{ 'list': 'ordered'}, { 'list': 'bullet' }],
							[{ 'indent': '-1'}, { 'indent': '+1' }],
							[{ 'direction': 'rtl' }, { 'align': [] }],
							['link', 'image', 'video', 'table'],
							['blockquote', 'code-block'],
							['clean']
						],
						handlers: {
							table: function () {
								insertTable(this.quill);
							}
						}
					}
				},
				placeholder: opts.placeholder || ''
			};
			if (opts.rtl) {
				quillConfig.direction = 'rtl';
			}

			var quill = new Quill('#' + editorId, quillConfig);
			ensureTableIcon(quill);

			if (textarea.value) {
				quill.root.innerHTML = textarea.value;
				enhanceTables(quill.root);
			}

			quill.on('text-change', function () {
				textarea.value = serializeHtml(quill.root.innerHTML);
			});

			var form = textarea.closest('form');
			if (form && textarea.getAttribute('data-quill-form-bound') !== '1') {
				textarea.setAttribute('data-quill-form-bound', '1');
				form.addEventListener('submit', function () {
					textarea.value = serializeHtml(quill.root.innerHTML);
				});
			}

			wrap.setAttribute('data-quill-initialized', '1');
		}

		function bindInit() {
			if (tabPaneId) {
				var pane = document.getElementById(tabPaneId);
				var tabBtn = document.querySelector('[data-bs-target="#' + tabPaneId + '"]');
				if (pane && pane.classList.contains('active')) {
					initEditor();
				}
				if (tabBtn) {
					if (wrap._cmsQuillTabHandler) {
						tabBtn.removeEventListener('shown.bs.tab', wrap._cmsQuillTabHandler);
					}
					wrap._cmsQuillTabHandler = initEditor;
					tabBtn.addEventListener('shown.bs.tab', initEditor);
				}
			} else {
				initEditor();
			}
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', bindInit);
		} else {
			bindInit();
		}
	}

	window.teardownCmsQuillRoot = teardownCmsQuillRoot;

	window.initCmsQuillRoots = function (root) {
		var scope = root && root.querySelectorAll ? root : document;
		var list = scope.querySelectorAll ? scope.querySelectorAll('.cms-quill-root') : [];
		Array.prototype.forEach.call(list, function (wrap) {
			mountOne(wrap);
		});
	};

	document.addEventListener('DOMContentLoaded', function () {
		window.initCmsQuillRoots(document);
	});
})();
</script>
@endonce
