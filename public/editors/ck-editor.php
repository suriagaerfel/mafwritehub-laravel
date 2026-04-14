<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>

<textarea id="editor"></textarea>

<script>
ClassicEditor
  .create(document.querySelector('#editor'))
  .then(editor => {
    console.log('Editor ready', editor);
  })
  .catch(error => {
    console.error(error);
  });
</script>

</body>
</html>