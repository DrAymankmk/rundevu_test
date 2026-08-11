@once
<link href="{{ asset('admin/css/quill.snow.css') }}" rel="stylesheet" type="text/css">
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

			var opts = parseOptions(wrap);
			var quillConfig = {
				theme: 'snow',
				modules: {
					toolbar: [
						[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
						[{ 'font': [] }],
						[{ 'size': [] }],
						['bold', 'italic', 'underline', 'strike'],
						[{ 'color': [] }, { 'background': [] }],
						[{ 'script': 'sub'}, { 'script': 'super' }],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],
						[{ 'indent': '-1'}, { 'indent': '+1' }],
						[{ 'direction': 'rtl' }, { 'align': [] }],
						['link', 'image', 'video'],
						['blockquote', 'code-block'],
						['clean']
					]
				},
				placeholder: opts.placeholder || ''
			};
			if (opts.rtl) {
				quillConfig.direction = 'rtl';
			}

			var quill = new Quill('#' + editorId, quillConfig);

			if (textarea.value) {
				quill.root.innerHTML = textarea.value;
			}

			quill.on('text-change', function () {
				textarea.value = quill.root.innerHTML;
			});

			var form = textarea.closest('form');
			if (form && textarea.getAttribute('data-quill-form-bound') !== '1') {
				textarea.setAttribute('data-quill-form-bound', '1');
				form.addEventListener('submit', function () {
					textarea.value = quill.root.innerHTML;
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
