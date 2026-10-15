import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const getCsrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const uploadImage = async (uploadUrl, file) => {
    const formData = new FormData();
    formData.append('image_file', file);

    const res = await fetch(uploadUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: formData,
    });

    if (!res.ok) {
        const text = await res.text().catch(() => '');
        throw new Error(text || 'Upload gagal');
    }

    const data = await res.json();
    if (!data?.url) throw new Error('Upload gagal');
    return data.url;
};

const buildToolbar = (uploadUrl) => {
    const base = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ align: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote'],
        ['link'],
        ['clean'],
    ];

    if (!uploadUrl) {
        return {
            container: base,
        };
    }

    const withImage = [...base];
    withImage.splice(withImage.length - 1, 0, ['image']);

    return {
        container: withImage,
    };
};

const initEditor = ({ editorEl, textarea, placeholder, uploadUrl }) => {
    if (!editorEl || !textarea) return;

    if (editorEl.dataset.quillReady === '1') return;
    editorEl.dataset.quillReady = '1';

    const quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: placeholder || 'Tulis di sini...',
        modules: {
            toolbar: buildToolbar(uploadUrl),
        },
    });

    const toolbarModule = quill.getModule('toolbar');
    if (toolbarModule && uploadUrl) {
        toolbarModule.addHandler('image', async () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.click();

            input.onchange = async () => {
                const file = input.files?.[0];
                if (!file) return;

                try {
                    const url = await uploadImage(uploadUrl, file);
                    const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
                    quill.insertEmbed(range.index, 'image', url, 'user');
                    quill.setSelection(range.index + 1, 0, 'user');
                } catch (e) {
                    alert(e?.message || 'Upload gagal');
                }
            };
        });
    }

    const initial = textarea.value || '';
    if (initial.trim()) {
        quill.clipboard.dangerouslyPasteHTML(initial);
    }

    const sync = () => {
        textarea.value = quill.root.innerHTML;
    };

    quill.on('text-change', sync);
    sync();

    const form = textarea.closest('form');
    if (form) {
        form.addEventListener('submit', () => {
            sync();
        });
    }
};

const init = () => {
    const customEditors = document.querySelectorAll('[data-quill-textarea-id]');
    if (customEditors.length) {
        customEditors.forEach((editorEl) => {
            const textareaId = editorEl.getAttribute('data-quill-textarea-id') || '';
            const textarea = textareaId ? document.getElementById(textareaId) : null;
            const placeholder = editorEl.getAttribute('data-placeholder') || '';
            const uploadUrl = editorEl.getAttribute('data-upload-url') || '';

            initEditor({ editorEl, textarea, placeholder, uploadUrl });
        });
        return;
    }

    const editorEl = document.getElementById('news-content-editor');
    const textarea = document.getElementById('news-content');
    if (!editorEl || !textarea) return;

    const uploadUrl = editorEl.getAttribute('data-upload-url') || '';
    initEditor({ editorEl, textarea, placeholder: 'Tulis isi berita di sini...', uploadUrl });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
