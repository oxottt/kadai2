<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .pagination { margin-top: 20px; }
        .pagination a { padding: 8px 16px; text-decoration: none; border: 1px solid #ddd; }
        .pagination a.active { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Users List</h1>

    <table>
        <thead>
            <tr>
                <th>Hash</th>
                <th>Name</th>
                <th>Family</th>
                <th>Key</th>
                <th>URL</th>
                <th>Image</th>
                <th>Last Update</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {% for user in users %}
            <tr>
                <td>{{ user.hash }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.family }}</td>
                <td>{{ user.data.key }}</td>
                <td><a href="{{ user.data.url }}" target="_blank">{{ user.data.url }}</a></td>
                <td>{{ user.data['img name'] }}</td>
                <td>{{ date('Y-m-d H:i:s', user.update) }}</td>
                <td>
                    <a href="{{ url('users/edit/' ~ user._id) }}">Edit</a>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="8">No users found</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>

    <div class="pagination">
        {% if currentPage > 1 %}
            <a href="?page={{ currentPage - 1 }}&limit={{ limit }}">Previous</a>
        {% endif %}

        {% for i in 1..totalPages %}
            <a href="?page={{ i }}&limit={{ limit }}" class="{% if i == currentPage %}active{% endif %}">{{ i }}</a>
        {% endfor %}

        {% if currentPage < totalPages %}
            <a href="?page={{ currentPage + 1 }}&limit={{ limit }}">Next</a>
        {% endif %}
    </div>
</body>
</html>