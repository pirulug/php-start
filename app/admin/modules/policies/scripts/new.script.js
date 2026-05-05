document.addEventListener('DOMContentLoaded', function () {
  const stTitle        = document.getElementById('st_title');
  const stSlug         = document.getElementById('st_slug');
  const stType         = document.getElementById('st_type');
  const editorMarkdown = document.getElementById('editor-markdown');
  const editorFaq      = document.getElementById('editor-faq');
  const faqContainer   = document.getElementById('faq-container');
  const addFaqBtn      = document.getElementById('add-faq');

  // SLUG TRANSFORMER
  const slugify = (text) => {
    return text.toString().toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Elimina acentos
      .replace(/\s+/g, '-')                            // Reemplaza espacios con -
      .replace(/[^\w\-]+/g, '')                       // Elimina caracteres no permitidos
      .replace(/\-\-+/g, '-')                         // Reemplaza múltiples - con uno solo
      .replace(/^-+/, '')                             // Elimina - al inicio
      .replace(/-+$/, '');                            // Elimina - al final
  };

  if (stTitle && stSlug) {
    stTitle.addEventListener('input', function () {
      if (!stSlug.dataset.edited || stSlug.value.trim() === '') {
        stSlug.value = slugify(this.value);
      }
    });

    stSlug.addEventListener('input', function () {
      this.dataset.edited = 'true';
    });
  }


  // SimpleMDE
  const simplemde = new SimpleMDE({
    element: document.getElementById('simplemde'),
    spellChecker: false,
    status: false,
    placeholder: 'Escribe aquí el contenido del documento...',
    toolbar: ['bold', 'italic', 'heading-2', 'heading-3', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'side-by-side', 'fullscreen', '|', 'guide']
  });

  simplemde.codemirror.on('change', function () {
    const value = simplemde.value();
    if (/^\#\s/m.test(value)) {
      simplemde.value(value.replace(/^\#\s/gm, '## '));
    }
  });

  // TYPE SWITCHER
  stType.addEventListener('change', function () {
    const type = this.value;
    if (type === 'faq') {
      editorMarkdown.style.display = 'none';
      editorFaq.style.display = 'block';
      editorFaq.classList.add('fade-in');
    } else {
      editorFaq.style.display = 'none';
      editorMarkdown.style.display = 'block';
      editorMarkdown.classList.add('fade-in');
      simplemde.codemirror.refresh();
    }
  });

  // FAQ UI Management
  const updateFaqNumbers = () => {
    faqContainer.querySelectorAll('.faq-item').forEach((item, index) => {
      item.querySelector('.faq-number').innerText = `Pregunta #${index + 1}`;
    });
  };

  addFaqBtn.addEventListener('click', function () {
    const html = `
      <div class="faq-item fade-in">
        <div class="faq-header">
          <span class="faq-number">Pregunta #</span>
          <button type="button" class="btn btn-sm faq-remove" title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
        </div>
        <div class="faq-body">
          <div class="mb-3">
            <label class="ghost-label small">Pregunta</label>
            <input type="text" name="faq_q[]" class="form-control fw-bold bg-body" placeholder="¿Cómo podemos ayudarte?">
          </div>
          <div>
            <label class="ghost-label small">Respuesta</label>
            <textarea name="faq_a[]" class="form-control bg-body" rows="3" placeholder="Escribe la respuesta detallada aquí..."></textarea>
          </div>
        </div>
      </div>`;
    faqContainer.insertAdjacentHTML('beforeend', html);
    updateFaqNumbers();
  });

  document.addEventListener('click', function (e) {
    const removeBtn = e.target.closest('.faq-remove');
    if (removeBtn) {
      const items = faqContainer.querySelectorAll('.faq-item');
      if (items.length > 1) {
        removeBtn.closest('.faq-item').remove();
        updateFaqNumbers();
      } else {
        alert('Debes mantener al menos una pregunta en la lista.');
      }
    }
  });
});
