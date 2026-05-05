document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('tag-input');
  if (input && typeof Tagify !== 'undefined') {
    new Tagify(input, {
      maxTags: 10,
      dropdown: {
        maxItems: 20,
        classname: "tags-look",
        enabled: 0,
        closeOnSelect: false
      }
    });
  }
});
