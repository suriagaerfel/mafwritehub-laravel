<link href="https://cdn.jsdelivr.net/npm/suneditor/dist/css/suneditor.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/suneditor/dist/suneditor.min.js"></script>

<textarea id="editor"></textarea>

<script>
const editor = SUNEDITOR.create('editor', {
  buttonList: [
    ['bold', 'italic', 'underline'],
    ['list', 'link', 'image', 'video']
  ]
});
</script>