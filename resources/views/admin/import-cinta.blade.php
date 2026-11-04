<form action="/admin/import-preview" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Preview</button>
</form>