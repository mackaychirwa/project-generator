<!DOCTYPE html>
<html>
<head>
    <title>Create Project</title>
</head>
<body>
    <h1>Create a New Project</h1>

    @if (session('status'))
        <p style="color: green;">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="projectForm" action="{{ route('project.create') }}" method="POST">
        @csrf

        <label for="name">Project Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="directory">Directory:</label>
        <input type="file" id="directory" name="directory" webkitdirectory>
        <input type="text" id="directoryPath" name="directoryPath">
        <p id="selectedDirectory"></p>

        <label for="language">Language:</label>
        <select id="language" name="language" required>
            <option value="laravel">Laravel</option>
            <option value="django">Django</option>
        </select>

        <button type="submit">Create Project</button>
    </form>

    <script>
        $('input[type=file]').change(function () {
                    console.log(this.files[0].mozFullPath);
                    document.getElementById('selectedDirectory').innerText = `Selected Directory: ${this.files[0].mozFullPath}`;
                // Set the directory path in a hidden input field
                document.getElementById('directoryPath').value = this.files[0].mozFullPath;

        });

    </script>
</body>
</html>
