<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>

    <form method="post">
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ user.name }}" required>
        </div>
        
        <div>
            <label>Family:</label>
            <input type="text" name="family" value="{{ user.family }}" required>
        </div>
        
        <div>
            <label>Key:</label>
            <input type="number" name="key" value="{{ user.data.key }}" required>
        </div>
        
        <div>
            <label>URL:</label>
            <input type="url" name="url" value="{{ user.data.url }}" required>
        </div>
        
        <div>
            <label>Image Name:</label>
            <input type="text" name="img_name" value="{{ user.data['img name'] }}" required>
        </div>
        
        <button type="submit">Save Changes</button>
        <a href="{{ url('users') }}">Cancel</a>
    </form>
</body>
</html>