import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

document.querySelectorAll('textarea[data-easymde]').forEach((el) => {
    if (!(el instanceof HTMLTextAreaElement)) {
        return;
    }

    const easyMDE = new EasyMDE({
        element: el,
        spellChecker: false,
        autofocus: false,
        status: false,
        minHeight: '280px',
        placeholder: el.getAttribute('placeholder') ?? '',
        // Garde le <textarea> aligné sur CodeMirror (sinon required + soumission voient un champ vide)
        forceSync: true,
        toolbar: [
            'bold',
            'italic',
            'heading',
            '|',
            'quote',
            'unordered-list',
            'ordered-list',
            '|',
            'link',
            '|',
            'preview',
            'side-by-side',
            'fullscreen',
            '|',
            'guide',
        ],
    });

    const form = el.closest('form');
    if (!form) {
        return;
    }

    // Avant la validation native du formulaire (après le dernier caractère saisi)
    form.addEventListener(
        'click',
        (e) => {
            const target = e.target;
            if (!(target instanceof Element)) {
                return;
            }
            const submitter = target.closest('button[type="submit"], input[type="submit"]');
            if (submitter && form.contains(submitter)) {
                easyMDE.codemirror.save();
            }
        },
        true,
    );
});
